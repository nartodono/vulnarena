<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if (!$middleware->isGetMethod()) {
    $middleware->errorResponse(405, "Only POST method is allowed");
    exit;
}

if (!$middleware->isAuthCheck()) {
    $middleware->errorResponse(401, "You must be logged in");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";
$user_id = $_SESSION['user_id'];

// get pvp history from db
$query ="SELECT match_id, public_token, played_at,
    player1_id, player1_score, player1_time, player1_mmr_before, player1_mmr_gain,
    player2_id, player2_score, player2_time, player2_mmr_before, player2_mmr_gain,
    winner_id, loser_id
    FROM match_history WHERE player1_id = ? OR player2_id = ? ORDER BY played_at DESC
";
$stmt = $db->prepare($query);
$stmt->bind_param('ii', $user_id, $user_id); // 'ii' untuk dua integer
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    // Cek posisi user (player1/player2)
    $isPlayer1 = $row['player1_id'] == $user_id;
    $mmr_before = $isPlayer1 ? $row['player1_mmr_before'] : $row['player2_mmr_before'];
    $mmr_gain   = $isPlayer1 ? $row['player1_mmr_gain'] : $row['player2_mmr_gain'];
    $score      = $isPlayer1 ? $row['player1_score'] : $row['player2_score'];
    if (empty($row['winner_id'])) {
        $resultText = 'Draw';
    } else if ($row['winner_id'] == $user_id) {
        $resultText = 'Win';
    } else {
        $resultText = 'Lose';
    }

    $history[] = [
        'match_id'    => $row['match_id'],
        'played_at'   => $row['played_at'],
        'result'      => $resultText,
        'mmr_before'  => $mmr_before,
        'mmr_gain'    => $mmr_gain,
        'score'       => $score,
        'public_token' => $row['public_token']
    ];
}

echo json_encode ([
    'success' => true,
    'user_id' => $user_id,
    'count' => count($history),
    'data' => $history
]);

exit;
?>