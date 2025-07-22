import { getCookie } from "./module.js";

document.addEventListener("DOMContentLoaded", () => {

    function showNotify(title, message) {
        document.getElementById("notifyTitle").textContent = title;
        document.getElementById("notifyMessage").textContent = message;
        document.getElementById("notifyModal").classList.remove("hidden");
        document.getElementById("notifyModal").classList.add("flex");
        // Reset handler supaya tidak double
        document.getElementById("notifyCloseBtn").onclick = null;
    }

    const changePwForm = document.getElementById('passwordChangeForm');

    changePwForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = new FormData(changePwForm);
        const jsonData = {};
        data.forEach((value, key) => {
            jsonData[key] = value;
        });

        try {
            const response = await fetch("https://api.vulnarena.space/user/change-password", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCookie("csrf_token") || ""
                },
                credentials: "include",
                body: JSON.stringify(jsonData)
            });

            let result = {};
            try {
                result = await response.json();
            } catch (err) {
                result = { success: false, message: "Unexpected server error" };
            }

            const okBtn = document.getElementById("notifyCloseBtn");
            if (result.success) {
                showNotify("SUKSES", "Password berhasil diubah. Silakan login ulang.");
                let notifyTimeout;
                function redirectToLogin() {
                    window.location.href = "/login";
                }
                okBtn.onclick = function() {
                    clearTimeout(notifyTimeout);
                    redirectToLogin();
                };
                notifyTimeout = setTimeout(redirectToLogin, 3000);
            } else {
                showNotify("Gagal", result.message || "Terjadi kesalahan");
                okBtn.onclick = function() {
                    document.getElementById("notifyModal").classList.add("hidden");
                    document.getElementById("notifyModal").classList.remove("flex");
                };
            }
        }
        catch (err) {
            console.error("Change password error:", err);
        }
    });

});
