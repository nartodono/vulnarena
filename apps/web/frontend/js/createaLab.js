import { getCookie } from "./module.js";
import { getLabInfo } from "./module.js";

document.addEventListener("DOMContentLoaded", () => {

    function showNotify(title, message) {
        document.getElementById("notifyTitle").textContent = title;
        document.getElementById("notifyMessage").textContent = message;
        document.getElementById("notifyModal").classList.remove("hidden");
        document.getElementById("notifyModal").classList.add("flex");
    }

    document.getElementById("notifyCloseBtn").addEventListener("click", () => {
        document.getElementById("notifyModal").classList.add("hidden");
        document.getElementById("notifyModal").classList.remove("flex");
    });

// timer
   let labCountdownTimer = null;

    function startLabCountdown(expiredUnix) {
        const labTime = document.getElementById("labTime");
        let countdown = expiredUnix - Math.floor(Date.now() / 1000);

        function updateCountdown() {
            if (countdown > 0) {
                labTime.textContent = formatCountdown(countdown);
                countdown--;
            } else {
                labTime.textContent = "Expired";
                clearInterval(labCountdownTimer);
                // opsional: loadLabInfo(); // auto-refresh status
            }
        }

        if (labCountdownTimer) clearInterval(labCountdownTimer); // prevent double
        updateCountdown();
        labCountdownTimer = setInterval(updateCountdown, 1000);
    }

    function formatCountdown(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m} menit ${s} detik`;
    }

    // Load lab information and update UI
    async function loadLabInfo() {
        const labInfo = await getLabInfo();
        
        if (labInfo === null) {
            showNotify("Error", "Failed to load lab information.");
        }
        else if (labInfo === false) {
            document.getElementById("labUnactiveStat").classList.remove("hidden");
            document.getElementById("labUnactiveStat").classList.add("flex");
            document.getElementById("labActiveStat").classList.add("hidden");
            document.getElementById("labActiveStat").classList.remove("flex");
        }
        else{
            
            document.getElementById("labActiveStat").classList.remove("hidden");
            document.getElementById("labActiveStat").classList.add("flex");
            document.getElementById("labUnactiveStat").classList.add("hidden");
            document.getElementById("labUnactiveStat").classList.remove("flex");

            document.getElementById("labStatus").textContent = 'Aktif';
            document.getElementById("labDomain").textContent = labInfo.subdomain;
            document.getElementById("labCreatedAt").textContent = new Date(labInfo.createtime * 1000).toLocaleString();
            document.getElementById("labExpiredAt").textContent = new Date(labInfo.expiredtime * 1000).toLocaleString();
            startLabCountdown(labInfo.expiredtime);
            document.getElementById("getLabBtn").innerHTML =`
            <a href="https://${labInfo.subdomain}" target="_blank" id="goToLabBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <button>Ke Lab</button>
            </a>
            `;
        }  
    }
    loadLabInfo();

    const createLabBtn = document.getElementById("createLabBtn");
    createLabBtn.addEventListener("click", async () => {

        createLabBtn.disabled = true;
        createLabBtn.textContent = "Membuat...";

        try {
            const response = await fetch("https://api.vulnarena.space/lab/create", {
                method: "POST",
                credentials: "include",
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": getCookie("csrf_token")
                }      
            });

            const result = await response.json();

            if (result.success) {
                showNotify("Sukses", "Lab berhasil dibuat!");
            } else if (result.message?.trim().toLowerCase() === "session expired") {
                window.location.href = "/account";
            } else {
                showNotify("Error", result.message);
            }
        } catch (error) {
            showNotify("Error", "An unexpected error occurred.");
        } finally {
            createLabBtn.disabled = false;
            createLabBtn.textContent = "Create Lab";
        }
        loadLabInfo();
    });

});