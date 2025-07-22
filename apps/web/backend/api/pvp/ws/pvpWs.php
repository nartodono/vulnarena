<?php
require __DIR__ . '/../../../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$db = require __DIR__ . "/../../../database/db.php";
$credential = require_once __DIR__ . "/../../../config/config.php";

function ensureDb($db, $credential) {
    if (!$db->ping()) {
        echo "[DB] Lost, reconnecting...\n";
        $db->close();
        $db = new mysqli(
            $credential['database']["server"],
            $credential['database']["username"],
            $credential['database']["password"],
            $credential['database']["database"]
        );
        if ($db->connect_error) {
            echo "[DB] Reconnect failed: " . $db->connect_error . "\n";
        } else {
            echo "[DB] Reconnected!\n";
        }
    }
    return $db;
}

class SimpleSocket implements MessageComponentInterface {
    private array $clients = [];         // user_id => $conn
    private array $activeMatches = [];   // match_id => [player1_id, player2_id, connections, ...]
    private array $userMatchMap = [];    // user_id => match_id

    protected array $users = [];
    protected \mysqli $db;

    public function __construct($db, $credential) {
        $this->db = $db;
        $this->credential = $credential;
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "🔌 New connection: {$conn->resourceId}\n";
        $this->db = ensureDb($this->db, $this->credential);

        // get user information since we can't use $_SESSION[]
        $sessionId = null;
        $cookies = $conn->httpRequest->getHeader('Cookie');

        foreach ($cookies as $cookieStr) {
            foreach (explode(';', $cookieStr) as $cookie) {
                // CEK DULU ADA '=' atau tidak, supaya explode tidak error
                if (strpos($cookie, '=') === false) continue;
                [$key, $val] = explode('=', $cookie, 2);
                if (trim($key) === 'PHPSESSID') {
                    $sessionId = trim($val);
                    break 2;
                }
            }
        }

        if ($sessionId) {
            // get session using php file
            $sessionFile = "/var/lib/php/sessions/sess_$sessionId";
            if (file_exists($sessionFile)) {
                $raw = file_get_contents($sessionFile);

                if (preg_match('/user_id\|i:(\d+)/', $raw, $match)) {
                    $userId = $match[1];
                    $this->users[$conn->resourceId] = [ 'user_id' => $userId ];
                    $this->clients[$userId] = $conn;

                } else{
                    $conn->close();
                    return;
                }
            }
            else {
                $conn->close();
                return;
            }
        }
        else {
            $conn->close();
            return;
        }
    }


    public function onClose(ConnectionInterface $conn) {
        $user = $this->users[$conn->resourceId] ?? null;

        if ($user) {
            // if user is disconnected/leave when searching for enemy, status should be 1 only
            $matchStatus = $this->checkMatchStatus($user['user_id']);
            if (in_array($matchStatus['status'], [1, 2])) {
                $matchId = $matchStatus['current_match_id'];

                $query = "SELECT player1_id, waiting FROM matches WHERE match_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("i", $matchId);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();

                if ($row && $row['player1_id'] == $user['user_id'] && $row['waiting'] == 1) {
                    $query = "DELETE FROM matches WHERE match_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->bind_param("i", $matchId);
                    $stmt->execute();
                    $stmt->close();
                }

                $this->updateMatchStatus(0, $user['user_id'], null);

            }
            // uncompleted, if user is disconnected during the 
            // else if ($matchStatus['status'] == 2) {
                // kalo lagi di match dan dc/keluar maka dianggap udah selesai, lgsg push score sementara ke matches,
                // orang terakhir yang akan push ke history
            // }
        }

        unset($this->users[$conn->resourceId]);
        echo "❎ Connection {$conn->resourceId} closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->db = ensureDb($this->db, $this->credential);
        echo "🔥 Error: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $this->db = ensureDb($this->db, $this->credential);
        $data = json_decode($msg, true);
        $user = $this->users[$from->resourceId] ?? null;

        if (!$data || !isset($data['action'])) {
            $from->send(json_encode([
                "status" => "error",
                "message" => "Format tidak valid, gunakan JSON dengan key 'action'."
            ]));
            return;
        }

        $action = $data['action'];

        // test only
        if ($action === "whoami") {
            if ($user) {
                $from->send(json_encode([
                    "status" => "success",
                    "message" => $user['user_id']
                ]));
            } else {
                $from->send(json_encode([
                    "status" => "error",
                    "message" => "❌ Kamu belum login."
                ]));
            }
            return;
        }
        // request to play pvp
        else if ($action === "play") {

            // Cek status pemain
            $matchStatus = $this->checkMatchStatus($user['user_id']);
            if($matchStatus['status'] === 1 || $matchStatus['status'] === 2) {
                $from->send(json_encode([
                    "debug" => "dah main",
                    "match" => $matchStatus
                ]));
                return;
            }

            // cari room kosong
            $matchInfo = $this->findMatch();
            
            // buat pertandingan baru jika tidak ada yang ditemukan
            if($matchInfo === null) {
                $newMatch = $this->createMatch($user['user_id']);
                $this->updateMatchStatus(1, $user['user_id'], $newMatch['match_id']);
                $from->send(json_encode([
                    "status" => "room",
                    "public_token" => $newMatch['public_token'],
                    "match_id" => $newMatch['match_id'],
                ]));
                return;
            }

            // bergabung ke pertandingan yang sudah ada
            $matchId = $matchInfo['match_id'];
            $success = $this->joinMatch($user['user_id'], $matchId);
            $this->updateMatchStatus(1, $user['user_id'], $matchId);
          
            // ambil info pemain
            $player2_id = $user['user_id'];
            $player1_id = null;

            $query = "SELECT player1_id FROM matches WHERE match_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $matchId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $player1_id = $row['player1_id'];
            }
            $stmt->close();

            // get player2 nickname
            $query = "SELECT nickname FROM user_profile WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $player2_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $player2_nickname = $row['nickname'];

            // get player 1 nickname
            $query = "SELECT nickname FROM user_profile WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $player1_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $player1_nickname = $row['nickname'];
            
            // Jika room penuh ditemukan, buat pertandingan aktif
            if($player1_id){
                $this->activeMatches[$matchId] = [
                    'player1_id' => $player1_id,
                    'player2_id' => $player2_id,
                    'connections' => [
                        $player1_id => $this->clients[$player1_id] ?? null,
                        $player2_id => $this->clients[$player2_id] ?? null
                    ],
                    'scores' => [
                        $player1_id => 0,
                        $player2_id => 0
                    ],
                    'current_question' => [
                        $player1_id => 0,
                        $player2_id => 0
                    ],
                    'answers' => [
                        $player1_id => [],
                        $player2_id => []
                    ],
                    'duration' => [
                        $player1_id => 0,
                        $player2_id => 0
                    ],
                    'finished' => [
                        $player1_id => false,
                        $player2_id => false
                    ],
                    'reward' => [
                        $player1_id => 0,
                        $player2_id => 0
                    ],
                    'start_time' => time()
                ];

                $this->userMatchMap[$player1_id] = $matchId;
                $this->userMatchMap[$player2_id] = $matchId;

                // uppdate status to db set 2 meaning user is playing the game
                $this->updateMatchStatus(2, $this->activeMatches[$matchId]['player1_id'], $matchId);
                $this->updateMatchStatus(2, $this->activeMatches[$matchId]['player2_id'], $matchId);

                //update matches table time
                $time = time();
                $query = "UPDATE matches SET played_at = ? WHERE match_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("ii", $time, $matchId);
                $stmt->execute();
                $stmt->close();

                // send info that match is ready to start
                foreach ($this->activeMatches[$matchId]['connections'] as $uid => $conn) {
                    $conn->send(json_encode([
                        'action' => 'start',
                        'match_id' => $matchId,
                        'user_id' => $uid == $player1_id ? $player1_id : $player2_id,
                        'enemy_id' => $uid == $player1_id ? $player2_id : $player1_id,
                        'user_nickname' => $uid == $player1_id ? $player1_nickname : $player2_nickname,
                        'enemy_nickname' => $uid == $player1_id ? $player2_nickname : $player1_nickname
                    ]));
                }
            }
        } 
        // start the match
        else if ($action === "start") {
            
            if (!isset($this->userMatchMap[$user['user_id']])) {
                $from->send(json_encode(["error" => "⚠️ Kamu belum masuk ke pertandingan."]));
                return;
            }

            $matchId = $this->userMatchMap[$user['user_id']];
            if (!isset($this->activeMatches[$matchId])) {
                $from->send(json_encode(["error" => "Match tidak ditemukan."]));
                return;
            }

            if (!empty($this->activeMatches[$matchId]['started'])) {
                $from->send(json_encode(["info" => "Match sudah dimulai."]));
                return;
            }

            $questions = $this->questionList();
            if (empty($questions)) {
                $from->send(json_encode(["error" => "Tidak ada soal."]));
                return;
            }

            // set temporary match info flag started to true
            $this->activeMatches[$matchId]['started'] = true;

            $matchId = $this->userMatchMap[$user['user_id']];
            // Mulai pertandingan
            $player1_id = $this->activeMatches[$matchId]['player1_id'];
            $player2_id = $this->activeMatches[$matchId]['player2_id'];

            $this->activeMatches[$matchId]['questions'] = $questions;
            $this->activeMatches[$matchId]['current_question'] = [
                $this->activeMatches[$matchId]['player1_id'] => 0,
                $this->activeMatches[$matchId]['player2_id'] => 0
            ];
            
            $question = $questions[0];

            // send first question at the same time for all players
            foreach ($this->activeMatches[$matchId]['connections'] as $uid => $conn) {
                $conn->send(json_encode([
                    "action" => "q1",
                    "question_id" => $question['id'],
                    "question" => $question['question'],
                    "options" => $question['options'],
                    "team" => $question['team'],
                    "question_number" => 1
                ]));
            }

        }         // if user send answer then save the answer and return new question
        else if ($action === "answer") {

            $questionIndex = (int) $data['question_number'] - 1; // indeks array mulai dari 0
            $questionAnswer = $data['question_answer'];
            $duration = $data['duration'];
            

            if (!isset($this->userMatchMap[$user['user_id']])) {
                $from->send(json_encode(["error" => "⚠️ Kamu belum masuk ke pertandingan."]));
                return;
            }

            $matchId = $this->userMatchMap[$user['user_id']];
            if (!isset($this->activeMatches[$matchId])) {
                $from->send(json_encode(["error" => "❌ Match tidak ditemukan."]));
                return;
            }

            $questions = $this->activeMatches[$matchId]['questions'];
            
            // Cek soal tersedia
            if (!isset($questions[$questionIndex])) {
                $from->send(json_encode(["error" => "❌ Soal ke-" . ($questionIndex + 1) . " tidak tersedia."]));
                return;
            }

            // simpan jawaban
            $this->activeMatches[$matchId]['answers'][$user['user_id']][$questionIndex] = $questionAnswer;

            // Update index soal untuk user
            $this->activeMatches[$matchId]['current_question'][$user['user_id']] = $questionIndex;

            // update durasi
            $this->activeMatches[$matchId]['duration'][$user['user_id']] = $duration;

            $nextIndex = $questionIndex + 1;

            // if user is already fill all the question but the opponent is still not done yet
            if (!isset($questions[$nextIndex])) {
                // evalute user score/correct answer and set flag finished to true
                $this->evaluateUserAnswer($matchId, $user['user_id']);

                $from->send(json_encode([
                    "action" => "finished",
                    "message" => "✅ Semua soal telah dijawab. Menunggu hasil..."
                ]));
                
                $finished = $this->activeMatches[$matchId]['finished'];
                $player1_id = $this->activeMatches[$matchId]['player1_id'];
                $player2_id = $this->activeMatches[$matchId]['player2_id'];

                // if both users already finish answer all the question
                if($finished[$player1_id] && $finished[$player2_id]) {
                    $score1 = $this->activeMatches[$matchId]['scores'][$player1_id];
                    $score2 = $this->activeMatches[$matchId]['scores'][$player2_id];

                    $duration1 = $this->activeMatches[$matchId]['duration'][$player1_id];
                    $duration2 = $this->activeMatches[$matchId]['duration'][$player2_id];

                    // evalute the winner
                    $winner = $this->evaluateMatchWinner($matchId);
                    $p1Result = $winner['p1Result'];
                    $p2Result = $winner['p2Result'];

                    // send result for both user
                    foreach ($this->activeMatches[$matchId]['connections'] as $uid => $conn) {
                        $conn->send(json_encode([
                            "action" => "result",
                            "your_score" => $this->activeMatches[$matchId]['scores'][$uid],
                            "enemy_score" => $uid === $player1_id ? $score2 : $score1,
                            "your_duration" => $this->activeMatches[$matchId]['duration'][$uid],
                            "enemy_duration" => $uid === $player1_id ? $duration2 : $duration1,
                            "result" => $uid === $player1_id ? $p1Result : $p2Result,
                            "time" => $this->activeMatches[$matchId]['start_time'],
                            'mmr_reward' => $this->activeMatches[$matchId]['reward'][$uid],
                        ]));
                    }

                    // remove temporary match info
                    unset($this->activeMatches[$matchId]);
                    unset($this->userMatchMap[$player1_id]);
                    unset($this->userMatchMap[$player2_id]);

                }
                return;
            }

            $nextQuestion = $questions[$nextIndex];

            // Kirim soal ke user
            $from->send(json_encode([
                "action" => "question",
                "question_id" => $nextQuestion['id'],
                "question" => $nextQuestion['question'],
                "options" => $nextQuestion['options'],
                "team" => $nextQuestion['team'],
                "question_number" => $nextIndex + 1
            ]));
        }
        // send match conclusion
        else if ($action === "conclusion"){
            // try and catch used for debugging before and not deleted yet
            try {
                $userId = $user['user_id'];
                $conclusion = $this->conclusionMatch($userId);

                $from->send(json_encode([
                    "action" => "conclusion",
                    "data" => $conclusion
                ]));
            } catch (Exception $e) {
                error_log("ERROR conclusion: " . $e->getMessage());
                $from->send(json_encode([
                    "action" => "error",
                    "message" => $e->getMessage()
                ]));
            }
            
        }
        // keep connection between client and server
        else if ($action === "ping") {
            $this->db = ensureDb($this->db, $this->credential);
            $from->send(json_encode([
                "action" => "pong"
            ]));
        }
        // unknown action
        else{
            $from->send(json_encode([
                "status" => "error",
                "message" => "undefined action found:",
                "received" => $data
            ]));
        }
    }

    // get match result for user
    private function conclusionMatch($userId){
        $query = "SELECT last_match_id, mmr FROM user_stats WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $matchId = $row['last_match_id'];
        $userMmr = $row['mmr'];

        $query = "SELECT public_token, played_at, 
            player1_id, player1_score, player1_time, player1_mmr_gain,
            player2_id, player2_score, player2_time, player2_mmr_gain,
            winner_id, loser_id FROM match_history WHERE match_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $matchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $userScore = null;
        $userTime = null;
        $userMmrGain = null;
        if($userId == $row['player1_id']){
            $userScore = $row['player1_score'];
            $userTime = $row['player1_time'];
            $userMmrGain = $row['player1_mmr_gain'];
        }
        else if($userId == $row['player2_id']){
            $userScore = $row['player2_score'];
            $userTime = $row['player2_time'];
            $userMmrGain = $row['player2_mmr_gain'];
        }

        if($userId == $row['winner_id']){
            $userWin = 1;
        }
        else if($userId == $row['loser_id']){
            $userWin = 0;
        }
        else{
            $userWin = 2;
        }

        $played_at = $row['played_at'];

        return [
            "matchId" => $matchId,
            "played_at" => $played_at,
            "userWin" => $userWin,
            "userMmr" => $userMmr,
            "userMmrGain" => $userMmrGain,
            "userScore" => $userScore,
            "userTime" => $userTime
        ];
    }

    // update match history and matches
    private function updateMatchHistory($matchId, $winner_id, $loser_id, $player1_mmr_before, $player2_mmr_before){
        $match = &$this->activeMatches[$matchId];
        
        $player1_id = $match['player1_id'];
        $player2_id = $match['player2_id'];

        $query = "SELECT public_token, played_at FROM matches WHERE match_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $matchId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $public_token = $row['public_token'];
        $played_at = $row['played_at'];

        $player1_score = $match['scores'][$player1_id];
        $player1_time = $match['duration'][$player1_id];
        $player1_mmr_gain = $match['reward'][$player1_id];

        $player2_score = $match['scores'][$player2_id];
        $player2_time = $match['duration'][$player2_id];
        $player2_mmr_gain = $match['reward'][$player2_id];

        // update history match
        $query = 
            "INSERT INTO match_history (match_id, public_token, played_at, 
            player1_id, player1_score, player1_time, player1_mmr_before, player1_mmr_gain,
            player2_id, player2_score, player2_time, player2_mmr_before, player2_mmr_gain,
            winner_id, loser_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isiiiiiiiiiiiii", $matchId, $public_token, $played_at,
            $player1_id, $player1_score, $player1_time, $player1_mmr_before, $player1_mmr_gain,
            $player2_id, $player2_score, $player2_time, $player2_mmr_before, $player2_mmr_gain,
            $winner_id, $loser_id);
        $stmt->execute();
        $stmt->close();

        // update matches
        $query = "DELETE FROM matches WHERE match_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $matchId);
        $stmt->execute();
        $stmt->close();

    }

    // update MMR for user
    private function updateMMR($user_id, $matchId, $matchResult, $mmrGain){

        $query = "SELECT mmr, highest_mmr, match_played, match_win, match_lost FROM user_stats WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $oldMmr = $row['mmr'];
        $newMmr = $oldMmr + $mmrGain;
        if ($newMmr < 0) $newMmr = 0;

        $newHighestMmr = $row['highest_mmr'];
        if($newMmr > $row['highest_mmr']){
            $newHighestMmr = $newMmr;
        }

        $newMatchPlayed = $row['match_played'] + 1;

        $newMatchWin = $row['match_win'];
        $newMatchLost = $row['match_lost'];
        if ($matchResult == 1){
            $newMatchWin = $row['match_win'] + 1;
        }
        else if ($matchResult == 0) {
            $newMatchLost = $row['match_lost'] + 1;
        }

        $query = "UPDATE user_stats 
                SET mmr = ?, highest_mmr = ?, match_played = ?, match_win = ?, match_lost = ?, last_match_id = ? 
                WHERE user_id = ?"
        ;

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiiiiii", $newMmr, $newHighestMmr, $newMatchPlayed, $newMatchWin, $newMatchLost, $matchId, $user_id);
        $stmt->execute();
        $stmt->close();

        return $oldMmr;
    }

    // cari pemenang
    private function evaluateMatchWinner($matchId){
        $match = &$this->activeMatches[$matchId];
        
        $player1_id = $match['player1_id'];
        $player2_id = $match['player2_id'];

        $p1Score = $match['scores'][$player1_id];
        $p1Duration = $match['duration'][$player1_id];
        $p2Score = $match['scores'][$player2_id];
        $p2Duration = $match['duration'][$player2_id];

        $p1Result = 0;
        $p1Reward = 0;
        $p2Result = 0;
        $p2Reward = 0;

        if ($p1Score > $p2Score){
            $p1Result = 1;

            // winner mmr
            $p1Reward = min(
                10
                + min(max(($p1Score - $p2Score) * 4, 0), 20)
                + (($p1Duration < $p2Duration) ? min(($p2Duration - $p1Duration) * 2, 10) : 0),
                40 // MAX reward winner
            );
            $p1MmrBefore = $this->updateMMR($player1_id, $matchId, $p1Result, $p1Reward);

            // loser mmr
            $p2Reward = max(
                -(
                    10
                    + min(max(($p1Score - $p2Score) * 2, 0), 10)
                    + (($p1Duration < $p2Duration) ? min(($p2Duration - $p1Duration) * 2, 10) : 0)
                ),
                -30 // MAX penalty for loser
            );
            $p2MmrBefore = $this->updateMMR($player2_id, $matchId, $p2Result, $p2Reward);

        }
        else if ($p1Score < $p2Score){
            $p2Result = 1;
            
            // winner mmr
            $p2Reward = min(
                10
                + min(max(($p2Score - $p1Score) * 4, 0), 20)
                + (($p2Duration < $p1Duration) ? min(($p1Duration - $p2Duration) * 2, 10) : 0),
                40 // MAX reward winner
            );
            $p2MmrBefore = $this->updateMMR($player2_id, $matchId, $p2Result, $p2Reward);

            // loser mmr
            $p1Reward = max(
                -(
                    10
                    + min(max(($p2Score - $p1Score) * 2, 0), 10)
                    + (($p2Duration < $p1Duration) ? min(($p1Duration - $p2Duration) * 2, 10) : 0)
                ),
                -30
            );
            $p1MmrBefore = $this->updateMMR($player1_id, $matchId, $p1Result, $p1Reward);
        }
        else{
            if($p1Duration < $p2Duration){
                $p1Result = 1;
                // winner mmr
                $p1Reward = 10 + min(($p2Duration - $p1Duration) * 2, 10);
                $p1MmrBefore = $this->updateMMR($player1_id, $matchId, $p1Result, $p1Reward);

                // loser mmr
                $p2Reward = max(-(10 + min(($p2Duration - $p1Duration) * 2, 10)), -30);
                $p2MmrBefore = $this->updateMMR($player2_id, $matchId, $p2Result, $p2Reward);

            }
            else if($p1Duration > $p2Duration){
                $p2Result = 1;
                // winner mmr
                $p2Reward = 10 + min(($p1Duration - $p2Duration) * 2, 10);
                $p2MmrBefore = $this->updateMMR($player2_id, $matchId, $p2Result, $p2Reward);

                // loser mmr
                $p1Reward = max(-(10 + min(($p1Duration - $p2Duration) * 2, 10)), -30);
                $p1MmrBefore = $this->updateMMR($player1_id, $matchId, $p1Result, $p1Reward);
            }
            else{  
                // draw
                $p1Result = 2;
                $p1Reward = 10;
                $p1MmrBefore = $this->updateMMR($player1_id, $matchId, $p1Result, $p1Reward);

                $p2Result = 2;
                $p2Reward = 10;
                $p2MmrBefore = $this->updateMMR($player2_id, $matchId, $p2Result, $p2Reward);
            }
        }

        $this->activeMatches[$matchId]['reward'][$player1_id] = $p1Reward;
        $this->activeMatches[$matchId]['reward'][$player2_id] = $p2Reward;

        $winner_id = null;
        $loser_id = null;
        if ($p1Result == 1){
            $winner_id = $player1_id;
            $loser_id = $player2_id;
        }
        else if ($p2Result == 1){
            $winner_id = $player2_id;
            $loser_id = $player1_id;
        }

        // update db
        $this->updateMatchHistory($matchId, $winner_id, $loser_id, $p1MmrBefore, $p2MmrBefore);

        return [
            'p1Result' => $p1Result,
            'p2Result' => $p2Result
        ];
    }

    // hitung skor
    private function evaluateUserAnswer($matchId, $userId) {
        $match = &$this->activeMatches[$matchId];
        $answers = $match['answers'][$userId] ?? [];
        $questions = $match['questions'];
        $score = 0;

        foreach ($answers as $index => $answer) {
            if (isset($questions[$index])) {
                $correctAnswer = $questions[$index]['answer'];
                if ($answer === $correctAnswer) {
                    $score += 1;
                }
            }
        }

        // save score and set finish flag for user
        $match['scores'][$userId] = $score;
        $match['finished'][$userId] = true;
    }
    
    // cek match 
    private function checkMatchInfo($match_id){
        $query = "SELECT public_token, player1_id, player2_id, rm_id FROM matches WHERE match_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $match_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return [
            'public_token' => $row['public_token'],
            'player1_id' => $row['player1_id'],
            'player2_id' => $row['player2_id'],
            'rm_id' => $row['rm_id']
        ];
    }

    // cek status pertandingan untuk user_id
    private function checkMatchStatus($user_id) {
        $query = "SELECT status, current_match_id FROM user_match_status WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return [
            'status' => $row['status'],
            'current_match_id' => $row['current_match_id'] ?? null
        ];
    }

    // update status pertandingan untuk user_id
    private function updateMatchStatus($newStatus, $user_id, $match_id){
        // 0 = not play, 1 = in waiting room, 2 = already start
        if ($match_id === null) {
            $query = "UPDATE user_match_status SET status = ?, current_match_id = NULL WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $newStatus, $user_id);
        } else {
            $query = "UPDATE user_match_status SET status = ?, current_match_id = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $newStatus, $match_id, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    }

    // cari pertandingan yang sedang menunggu
    private function findMatch() {
        $query = "SELECT match_id, public_token FROM matches WHERE waiting = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return null; // Tidak ada match
        }

        $row = $result->fetch_assoc();
        $stmt->close();

        return [
            'match_id' => $row['match_id'],
            'public_token' => $row['public_token']
        ];
    }

    // bergabung ke pertandingan yang sudah ada
    private function joinMatch($user_id, $match_id) {
        $query = "UPDATE matches SET waiting = 0, player2_id = ? WHERE match_id = ? AND waiting = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $user_id, $match_id);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return $success;
    }

    // buat pertandingan baru
    private function createMatch($user_id){
        do {
            $match_id = rand(10000000, 99999999);
            $query = "
                SELECT match_id FROM matches WHERE match_id = ?
                UNION
                SELECT match_id FROM match_history WHERE match_id = ?
                LIMIT 1
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $match_id, $match_id);
            $stmt->execute();
            $stmt->store_result();
        } while ($stmt->num_rows !== 0);
        $stmt->free_result();
        $stmt->close();

        do {
            $public_token = bin2hex(random_bytes(8));
            $query = "
                SELECT public_token FROM matches WHERE public_token = ?
                UNION
                SELECT public_token FROM match_history WHERE public_token = ?
                LIMIT 1
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $public_token, $public_token);
            $stmt->execute();
            $stmt->store_result();
        } while ($stmt->num_rows !== 0);
        $stmt->free_result();
        $stmt->close();

        $query = "INSERT INTO matches (match_id, public_token, player1_id, rm_id, waiting) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isii", $match_id, $public_token, $user_id, $user_id);
        $stmt->execute();
        $stmt->close();

        return [
            'match_id' => $match_id,
            'public_token' => $public_token,
        ];
    }

    private function questionList(){
        $redQuestions = [
            [
                "id" => 1,
                "question" => "Saat menemukan subdomain 'dev.vulnarena.space', apa langkah eksplorasi yang tepat?",
                "options" => [
                    "A" => "Nonaktifkan DNS resolver",
                    "B" => "Upload file gambar",
                    "C" => "Cek directory sensitif via brute-force",
                    "D" => "Langsung login sebagai admin"
                ],
                "answer" => "C",
                "team" => "Red",
                "explanation" => "Subdomain dev biasanya menyimpan file uji coba atau konfigurasi. Directory brute-force dapat mengungkap endpoint sensitif."
            ],
            [
                "id" => 2,
                "question" => "Dalam XSS stored, bagaimana cara mencuri sesi admin?",
                "options" => [
                    "A" => "Kirim form kosong",
                    "B" => "Tulis alert biasa",
                    "C" => "Tambahkan <script>alert('XSS')</script>",
                    "D" => "Sisipkan payload untuk mencuri cookie admin"
                ],
                "answer" => "D",
                "team" => "Red",
                "explanation" => "Stored XSS memungkinkan eksekusi skrip berbahaya yang dilihat oleh admin, seperti mencuri cookie sesi."
            ],
            [
                "id" => 3,
                "question" => "Form login tampaknya rentan terhadap SQL Injection. Input apa yang bisa digunakan untuk menguji kerentanan tersebut?",
                "options" => [
                    "A" => "admin@site.com",
                    "B" => "123456",
                    "C" => "' OR '1'='1",
                    "D" => "https://example.com"
                ],
                "answer" => "C",
                "team" => "Red",
                "explanation" => "Payload sederhana seperti `' OR '1'='1` dapat digunakan untuk menguji apakah input diinterpretasikan sebagai SQL."
            ],
            [
                "id" => 4,
                "question" => "Payload '& whoami' gagal dieksekusi. Apa solusi berikutnya?",
                "options" => [
                    "A" => "Tambah CSS",
                    "B" => "Pakai karakter '|', ';', atau '&&'",
                    "C" => "Logout user",
                    "D" => "Reset form"
                ],
                "answer" => "B",
                "team" => "Red",
                "explanation" => "Karakter chaining lain seperti `|` atau `;` sering digunakan untuk mencoba eksploitasi command injection."
            ],
            [
                "id" => 5,
                "question" => "Diberi '403 Forbidden' saat akses '/admin'. Apa teknik bypass?",
                "options" => [
                    "A" => "Ganti warna browser",
                    "B" => "Login ulang",
                    "C" => "Pakai path encoding seperti '%2e/admin'",
                    "D" => "Matikan sistem"
                ],
                "answer" => "C",
                "team" => "Red",
                "explanation" => "Path encoding dapat digunakan untuk mencoba melewati kontrol akses direktori."
            ],
            [
                "id" => 6,
                "question" => "Setelah akses '/etc/passwd', langkah selanjutnya...?",
                "options" => [
                    "A" => "Cek cron dan ssh key",
                    "B" => "Reboot server",
                    "C" => "Ubah password root",
                    "D" => "Install antivirus"
                ],
                "answer" => "A",
                "team" => "Red",
                "explanation" => "File seperti cron dan authorized_keys sering digunakan untuk pivoting atau persistence."
            ],
            [
                "id" => 7,
                "question" => "Query SQL apa untuk mencuri akun?",
                "options" => [
                    "A" => "INSERT * FROM users",
                    "B" => "DELETE FROM users;",
                    "C" => "SELECT user, password FROM users;",
                    "D" => "UPDATE users SET admin=1"
                ],
                "answer" => "C",
                "team" => "Red",
                "explanation" => "Query SELECT digunakan untuk membaca data, termasuk mencuri user dan password."
            ],
            [
                "id" => 8,
                "question" => "Bagaimana persistence di Linux target?",
                "options" => [
                    "A" => "Edit CSS file",
                    "B" => "Tambah cronjob dengan reverse shell",
                    "C" => "Kirim email spam",
                    "D" => "Menghapus history bash"
                ],
                "answer" => "B",
                "team" => "Red",
                "explanation" => "Menambahkan shell ke cron memungkinkan koneksi ulang berkala ke attacker."
            ],
            [
                "id" => 9,
                "question" => "Output command injection muncul tapi tidak efektif. Solusi?",
                "options" => [
                    "A" => "Jalankan alert lagi",
                    "B" => "Exfil data via DNS atau request HTTP",
                    "C" => "Reset cookie",
                    "D" => "Logout semua user"
                ],
                "answer" => "B",
                "team" => "Red",
                "explanation" => "Jika output tak terlihat, gunakan saluran alternatif seperti DNS tunneling untuk exfiltrasi data."
            ],
            [
                "id" => 10,
                "question" => "File '.php.jpg' berhasil diupload. Tujuannya?",
                "options" => [
                    "A" => "Membuat tampilan baru",
                    "B" => "Menyisipkan payload eksekusi",
                    "C" => "Menambah ukuran storage",
                    "D" => "Backup file penting"
                ],
                "answer" => "B",
                "team" => "Red",
                "explanation" => "File polyglot dapat digunakan untuk melewati filter dan tetap bisa dieksekusi di server."
            ],
        ];

        $blueQuestions = [
            [
                "id" => 1,
                "question" => "Terlihat request ke '/../../etc/passwd'. Ini indikasi...?",
                "options" => [
                    "A" => "Access Control",
                    "B" => "Path Traversal",
                    "C" => "Brute Force",
                    "D" => "DNS Rebind"
                ],
                "answer" => "B",
                "team" => "Blue",
                "explanation" => "Pattern '../../' adalah tanda khas dari serangan Path Traversal untuk mengakses file sensitif di luar direktori aplikasi."
            ],
            [
                "id" => 2,
                "question" => "Setelah alert SQLi, langkah awal tim?",
                "options" => [
                    "A" => "Hapus log",
                    "B" => "Nonaktifkan form",
                    "C" => "Korelasi log dan cek pattern input",
                    "D" => "Update halaman"
                ],
                "answer" => "C",
                "team" => "Blue",
                "explanation" => "Tim perlu mengidentifikasi asal serangan dan menghubungkannya dengan input serta aktivitas di aplikasi."
            ],
            [
                "id" => 3,
                "question" => "Apa kombinasi terbaik cegah XSS?",
                "options" => [
                    "A" => "Minify JS + ganti font",
                    "B" => "Escape output + gunakan CSP",
                    "C" => "Ganti warna tombol",
                    "D" => "Validasi password"
                ],
                "answer" => "B",
                "team" => "blue",
                "explanation" => "Kombinasi encoding output dan header Content-Security-Policy adalah teknik umum pencegahan XSS."
            ],
            [
                "id" => 4,
                "question" => "Semua user bisa akses '/admin/settings'. Solusinya?",
                "options" => [
                    "A" => "Ganti nama folder",
                    "B" => "Pasang CAPTCHA",
                    "C" => "Implementasi role-check dan auth middleware",
                    "D" => "Reset semua akun"
                ],
                "answer" => "C",
                "team" => "Blue",
                "explanation" => "Harus ada kontrol akses berbasis peran agar halaman admin tidak bisa diakses semua user."
            ],
            [
                "id" => 5,
                "question" => "Banyak request DNS ke domain acak. Ini bisa jadi...?",
                "options" => [
                    "A" => "Serangan MITM",
                    "B" => "DNS tunneling",
                    "C" => "Normal traffic",
                    "D" => "Cache expired"
                ],
                "answer" => "B",
                "team" => "Blue",
                "explanation" => "Aktivitas mencurigakan DNS random bisa jadi metode exfiltrasi data tersembunyi (DNS tunneling)."
            ],
            [
                "id" => 6,
                "question" => "Log mana relevan untuk deteksi command injection?",
                "options" => [
                    "A" => "Log background color",
                    "B" => "Log query SQL",
                    "C" => "Log output dari perintah shell",
                    "D" => "Log dari login user"
                ],
                "answer" => "C",
                "team" => "Blue",
                "explanation" => "Command injection menghasilkan eksekusi perintah shell. Log yang mencatat output atau proses shell relevan untuk mendeteksi serangan."
            ],
            [
                "id" => 7,
                "question" => "SQLi masih terjadi meski pakai prepared statement. Kemungkinan...?",
                "options" => [
                    "A" => "Bug di CSS",
                    "B" => "Masih ada raw query di bagian lain",
                    "C" => "DNS salah",
                    "D" => "Cookie bocor"
                ],
                "answer" => "B",
                "team" => "Blue",
                "explanation" => "Prepared statements harus digunakan secara konsisten. Jika SQLi masih terjadi, kemungkinan ada query yang belum diamankan."
            ],
            [
                "id" => 8,
                "question" => "Teknik proteksi upload webshell?",
                "options" => [
                    "A" => "Rename file & validasi ekstensi",
                    "B" => "Gunakan resolusi tinggi",
                    "C" => "Aktifkan dark mode",
                    "D" => "Sembunyikan tombol upload"
                ],
                "answer" => "A",
                "team" => "Blue",
                "explanation" => "Validasi tipe file dan nama file dapat mencegah penyusupan file eksekusi seperti webshell."
            ],
            [
                "id" => 9,
                "question" => "Apa header HTTP yang bisa bantu cegah XSS?",
                "options" => [
                    "A" => "X-Powered-By",
                    "B" => "Strict-Transport-Security",
                    "C" => "Content-Security-Policy",
                    "D" => "Allow-Origin"
                ],
                "answer" => "C",
                "team" => "Blue",
                "explanation" => "Content-Security-Policy (CSP) membatasi sumber daya yang bisa dijalankan oleh browser, efektif mencegah XSS."
            ],
            [
                "id" => 10,
                "question" => "Cookie tanpa flag 'HttpOnly' berisiko terhadap...?",
                "options" => [
                    "A" => "Serangan CSRF",
                    "B" => "Sniffing jaringan",
                    "C" => "Serangan XSS yang curi cookie",
                    "D" => "Kerusakan format gambar"
                ],
                "answer" => "C",
                "team" => "Blue",
                "explanation" => "Cookie tanpa HttpOnly bisa diakses lewat JavaScript. Jika ada XSS, maka session cookie dapat dicuri."
            ],
        ];

        shuffle($redQuestions);
        shuffle($blueQuestions);

        $redQuestions = array_slice($redQuestions, 0, 5);
        $blueQuestions = array_slice($blueQuestions, 0, 5);

        $mixed = [];
        $maxLength = max(count($redQuestions), count($blueQuestions));

        for ($i = 0; $i < 5; $i++) {
        // Red
        if (isset($redQuestions[$i])) {
            $mixed[] = $redQuestions[$i];
            }
            // Blue
            if (isset($blueQuestions[$i])) {
                $mixed[] = $blueQuestions[$i];
            }
        }

        return $mixed;
    }

    //EOF
}

// $server = IoServer::factory(
//     new HttpServer(new WsServer(new SimpleSocket())),
//     8080
// );

$chat = new SimpleSocket($db, $credential);
$server = IoServer::factory(
    new HttpServer(new WsServer($chat)),
    8080
);

echo "🚀 WebSocket Server running on port 8080...\n";
$server->run();