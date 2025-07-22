import { getCookie } from "./module.js";

document.addEventListener("DOMContentLoaded", () => {
    const userInfoRaw = sessionStorage.getItem('user_info');
    const userInfo = userInfoRaw ? JSON.parse(userInfoRaw) : null;

    // document.getElementById('username').textContent = userInfo.nickname;

    let socket = null; // <- deklarasi socket global
    function openSocket() {
        socket = new WebSocket("wss://api.vulnarena.space/ws");

        socket.onopen = () => {
            socket.send(JSON.stringify({ action: "whoami" }));

            // Start heartbeat
            // heartbeatInterval = setInterval(() => {
            //     if (socket.readyState === WebSocket.OPEN) {
            //         socket.send(JSON.stringify({ action: "ping" }));
            //     }
            // }, 20000); // setiap 20 detik
        };

        socket.onmessage = (e) => {
            console.log("📩 Message from server:", e.data);
            const out = document.getElementById("output");
            const div = document.createElement("div");
            div.textContent = e.data;
            out.appendChild(div);
        };
    }

    document.getElementById('req').addEventListener('submit', async function (e) {
        e.preventDefault();

        try {
            const response = await fetch('https://api.vulnarena.space/play', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": getCookie("csrf_token")
                }
            });

            let result = {};
            try {
                result = await response.json();
            } catch {
                result = { success: false, message: "Unexpected server error" };
            }

            if (result.success) {
                openSocket();
                
            } else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
            } else {
                throw new Error(result.message || "Failed to start the game");
            }
        } catch (error) {
            console.error("Error:", error);
            alert(error.message || "An error occurred while starting the game");
        }
    });

    document.getElementById("wsForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const msg = document.getElementById("msg").value;
        if (socket && socket.readyState === WebSocket.OPEN) {
            socket.send(JSON.stringify({ action: msg }));
            console.log("🚀 Dikirim:", msg);
            document.getElementById("msg").value = "";
        } else {
            alert("WebSocket belum siap!");
        }
    });
});