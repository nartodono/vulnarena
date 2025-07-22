import { getUserInfo } from "./module.js";

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

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("expired") === "true") {
        showNotify("Sesi Berakhir", "Sesi telah berakhir, silakan masuk kembali.");

        setTimeout(() => {
            document.getElementById("notifyModal").classList.add("hidden");
            document.getElementById("notifyModal").classList.remove("flex");
            urlParams.delete("expired");
        }, 3000);
    }

    const form = document.getElementById("loginForm");
    const pw = document.forms['loginForm'].elements['password'];

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const data = new FormData(form);
        const jsonData = {};

        data.forEach((value,key) => {
            jsonData[key] = value;
        });

        const submitBtn = form.querySelector("button[type=submit]");
        submitBtn.disabled = true;
        submitBtn.textContent = "Mohon tunggu...";
        
        try {
            const response = await fetch("https://api.vulnarena.space/auth/login", {
                method: "POST",
                credentials: "include",
                headers: {
                    "content-type":"application/json"
                },
                body: JSON.stringify(jsonData)
            });

            let result = {};
            
            try{
                result = await response.json(); 
            }
            catch (err){
                result = { success: false, message: "Unexpected server error" };
            }

            if (result.success) {
                await getUserInfo();
                window.location.href = "/";
            }
            else {
                showNotify("Failed", result.message);
                pw.value = "";
            }

        } catch (err) {
            console.error("Something went wrong: ", err);
        }
        finally{
            submitBtn.disabled = false;
            submitBtn.textContent = "🔐 Masuk Sekarang";
        }
    });


});

// ====== ajax