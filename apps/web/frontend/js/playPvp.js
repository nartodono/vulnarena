document.addEventListener("DOMContentLoaded", () => {
    let socket = null;
    let heartbeatInterval = null;

    const questionNumber = document.getElementById('questionNumber');
    const questionText = document.getElementById('questionText');
    const questionOptionA = document.getElementById('questionOptionA');
    const questionOptionB = document.getElementById('questionOptionB');
    const questionOptionC = document.getElementById('questionOptionC');
    const questionOptionD = document.getElementById('questionOptionD');
    const questionType = document.getElementById('questionType');

    // TIMER
    function Timer(displaySelector){
        let startTime = null;
        let interval = null;
        let elapsed = 0;

        function start() {
            startTime = Date.now();
            elapsed = 0;
            if(interval) clearInterval(interval);
            interval = setInterval(()=> {
                elapsed = Math.floor((Date.now() - startTime) / 1000);
                if(displaySelector) {
                    const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
                    const seconds = (elapsed % 60).toString().padStart(2, '0');
                    document.querySelector(displaySelector).textContent = `${minutes}:${seconds}`;
                }
            }, 1000);
        }

        function stop() {
            if (interval) clearInterval(interval);
            interval = null;
        }

        function reset() {
            stop();
            startTime = null;
            elapsed = 0;
            if(displaySelector) {
                document.querySelector(displaySelector).textContent = "00:00";
            }
        }

        function getElapsed() {
            return elapsed;
        }

        return { start, stop, reset, getElapsed}

    }

    const quizTimer = Timer('#showTimer');

    function updateQuestion(msg) {
        questionNumber.textContent = msg.question_number;
        questionText.textContent = msg.question;
        questionOptionA.textContent = msg.options["A"];
        questionOptionB.textContent = msg.options["B"];
        questionOptionC.textContent = msg.options["C"];
        questionOptionD.textContent = msg.options["D"];
        questionType.textContent = msg.team;

        // Reset pilihan
        selectedOption = null;
        document.querySelectorAll('.answer-option').forEach(b => {
            b.classList.remove('bg-green-600', 'text-white', 'ring-2', 'ring-green-400');
            b.classList.add('bg-indigo-600');
        });
    }

    let questionNum = null;

    // get answer
    let selectedOption = null;

    document.querySelectorAll('.answer-option').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.answer-option').forEach(b => {
          b.classList.remove('bg-green-600', 'text-white', 'ring-2', 'ring-green-400');
          b.classList.add('bg-indigo-600');
        });
        btn.classList.remove('bg-indigo-600');
        btn.classList.add('bg-green-600', 'text-white', 'ring-2', 'ring-green-400');

        selectedOption = btn.getAttribute('data-option');
      });
    });

    document.getElementById('answerBtn').addEventListener('click', function() {
        if(!selectedOption) {
            alert('Pilih dulu jawabannya!');
            return;
        }

        const answerPayload = {
            action: "answer",
            question_number: document.getElementById('questionNumber').textContent,
            question_answer: selectedOption,
            duration: quizTimer.getElapsed()
        };

        if(socket && socket.readyState === WebSocket.OPEN){
            socket.send(JSON.stringify(answerPayload));
            document.getElementById('answerBtn').disabled = true;
        }
        else{
            alert('koneksi error');
            document.getElementById('answerBtn').disabled = false;
        }
    });

    document.getElementById('resultNextBtn').addEventListener('click', function (){
        const conclusionPayload = {
            action: "conclusion"
        };
        console.log("conclusion sent!");

        if(socket && socket.readyState === WebSocket.OPEN){
            socket.send(JSON.stringify(conclusionPayload));
            document.getElementById('resultNextBtn').disabled = true;
        }
        else{
            alert('koneksi error');
            document.getElementById('resultNextBtn').disabled = false;
        }

    });

    // open ws
    function openSocket() {
        socket = new WebSocket("wss://api.vulnarena.space/ws");

        socket.onopen = () => {
            
            // Kirim pesan dalam bentuk JSON
            socket.send(JSON.stringify({ action: "play" }));
            
            // Heartbeat setiap 20 detik
            heartbeatInterval = setInterval(() => {
                if (socket.readyState === WebSocket.OPEN) {
                    socket.send(JSON.stringify({ action: "ping" }));
                }
            }, 20000);
        };

        socket.onmessage = (e) => {
            let msg = [];
            try {
                
                msg = JSON.parse(e.data);
                console.log("📩 JSON from server:", msg);

                questionNum = msg.question_number;
            } catch (err) {
                console.warn("❌ Gagal parse JSON dari server:", e.data);
            }

                if (msg.action === "start") {
                    // tampilkan nama lawan
                    document.getElementById('foundEnemyNickname').textContent = msg.enemy_nickname;
                    document.getElementById('foundEnemyText').classList.remove('hidden');
                    document.getElementById('searchEnemyText').classList.add('hidden');

                    document.getElementById('userName').textContent = msg.user_nickname;
                    document.getElementById('enemyName').textContent = msg.enemy_nickname;
                    document.getElementById('userId').textContent = msg.user_id;
                    document.getElementById('enemyId').textContent = msg.enemy_id;
                    document.getElementById('matchId').textContent = msg.match_id;

                    document.getElementById('yourName').textContent = msg.user_nickname;
                    document.getElementById('yourId').textContent = msg.user_id;
                    document.getElementById('opponentName').textContent = msg.enemy_nickname;
                    document.getElementById('opponentId').textContent = msg.enemy_id;
                    document.getElementById('resMatchId').textContent = msg.match_id;

                    setTimeout(() => {
                        socket.send(JSON.stringify({ action: "start" }));
                    }, 3000);
                }
                else if (msg.action === "q1") {
                    // hanya ini yang ubah tampilan halaman
                    document.getElementById('searchEnemyBar').classList.add('hidden');
                    document.getElementById('mainBar').classList.remove('hidden');
                    document.getElementById('timeBar').classList.remove('hidden');

                    // lalu update soal seperti biasa
                    updateQuestion(msg);
                    quizTimer.start();
   
                }
                else if (msg.action === "question") {
                    // untuk soal ke-2, ke-3, dst — cukup update isi soal saja
                    updateQuestion(msg);
                    document.getElementById('answerBtn').disabled = false;
                }
                else if (msg.action === "finished") {
                    document.getElementById('mainBar').classList.add('hidden');
                    document.getElementById('timeBar').classList.add('hidden');

                    document.getElementById('finishBar').classList.remove('hidden');
                    document.getElementById('waitingResultBar').classList.remove('hidden');
                }
                else if (msg.action === "result"){
                    document.getElementById('yourScore').textContent = msg.your_score;
                    document.getElementById('opponentScore').textContent = msg.enemy_score;

                    document.getElementById('ResultNextBtnBar').classList.remove('hidden');
                    document.getElementById('waitingResultBar').classList.add('hidden');
                    document.getElementById('resultBar').classList.remove('hidden');

                    const yourResult = document.getElementById('yourResult');
                    const opponentResult = document.getElementById('opponentResult');

                    const date = new Date(msg.time * 1000);

                    // Format tanggal
                    const tanggal = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    // Ambil jam dan menit, lalu gabung pakai titik dua
                    const jam = date.getHours().toString().padStart(2, '0');
                    const menit = date.getMinutes().toString().padStart(2, '0');
                    const jamMenit = `${jam}:${menit}`; 

                    // Gabungkan semuanya
                    const fixDate = `${tanggal}, ${jamMenit} WIB`;

                    document.getElementById('startTime').textContent = fixDate;

                    if (msg.result === 1){
                        yourResult.textContent = "MENANG";
                        yourResult.classList.add('text-green-400');

                        opponentResult.textContent = "KALAH";
                        opponentResult.classList.add('text-red-400');

                        document.getElementById('resultBar').classList.add('glow-green');
                    }
                    else if (msg.result === 0){
                        yourResult.textContent = "KALAH";
                        yourResult.classList.add('text-red-400');

                        opponentResult.textContent = "MENANG";
                        opponentResult.classList.add('text-green-400');
                        document.getElementById('resultBar').classList.add('glow-red');
                    }
                    else {
                        yourResult.textContent = "SERI";
                        opponentResult.textContent = "SERI";
                        document.getElementById('resultBar').classList.add('glow-universal');
                    }

                    document.getElementById('yourDuration').textContent = msg.your_duration;
                    document.getElementById('opponentDuration').textContent = msg.enemy_duration;

                }
                else if(msg.action === "conclusion"){
                    const epoch = msg.data.played_at; // Contoh: 1751206800
                    const date = new Date(epoch * 1000); // Kalikan 1000 agar jadi ms

                    // Format ke string lokal, misal: "21 Jun 2025 15:22"
                    const tanggal = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                    
                    // Format jam: 21:40
                    const jam = date.getHours().toString().padStart(2, '0');
                    const menit = date.getMinutes().toString().padStart(2, '0');
                    const jamMenit = `${jam}:${menit}`;
                    const formatted = `${tanggal}, ${jamMenit}`;

                    document.getElementById('finishBar').classList.add('hidden');
                    

                    document.getElementById('conclusionMatchId').textContent = msg.data.matchId;
                    document.getElementById('conclusionTime').textContent = formatted;

                    const statusElem = document.getElementById('conclusionStatus');
                    const rewardElem = document.getElementById('conclusionReward');
                    statusElem.classList.remove('text-green-400', 'text-red-400', 'text-gray-400');
                    rewardElem.classList.remove('text-green-400', 'text-red-400', 'text-gray-400');
                    if(msg.data.userWin == 1){
                        statusElem.textContent = "Menang";
                        statusElem.classList.add('text-green-400');
                        rewardElem.classList.add('text-green-400');
                    } else if(msg.data.userWin == 0){
                        statusElem.textContent = "Kalah";
                        statusElem.classList.add('text-red-400');
                        rewardElem.classList.add('text-red-400');
                    } else {
                        statusElem.textContent = "Seri";
                        statusElem.classList.add('text-gray-400');
                        rewardElem.classList.add('text-gray-400');
                    }

                    document.getElementById('conclusionMmr').textContent = msg.data.userMmr;
                    const mmrGain = msg.data.userMmrGain;
                    const mmrGainStr = mmrGain > 0 ? `+${mmrGain}` : mmrGain;
                    document.getElementById('conclusionReward').textContent = mmrGainStr;
                    document.getElementById('conclusionScore').textContent= msg.data.userScore;
                    document.getElementById('conclusionDuration').textContent = msg.data.userTime

                    document.getElementById('conclusionBar').classList.remove('hidden');

                    if (socket && socket.readyState === WebSocket.OPEN) {
                        socket.close();
                    }
                }

        };

        socket.onerror = (e) => {
            console.error("WebSocket error:", e);
        };

        socket.onclose = () => {
            console.log("❌ WebSocket closed");
            if (heartbeatInterval) clearInterval(heartbeatInterval);
        };
    }

    openSocket();
});
