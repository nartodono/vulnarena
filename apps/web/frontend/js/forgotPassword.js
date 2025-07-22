document.addEventListener("DOMContentLoaded", () => {
    let resetToken = "";

    const emailForm = document.getElementById('emailForm');
    const submitEmailBtn = document.getElementById('submitEmailBtn');

    const otpForm = document.getElementById('otpForm');
    const submitOtpBtn = document.getElementById('submitOtpBtn');

    const passwordForm = document.getElementById('passwordForm');
    const submitPasswordBtn = document.getElementById('submitPasswordBtn');

    function handleBeforeUnload(e) {
        e.preventDefault();
        e.returnValue = "Verifikasi belum selesai. Keluar sekarang akan mengulang proses dari awal. Lanjutkan?";
    }
    
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

    // SUBMIT EMAIL 
    emailForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        submitEmailBtn.disabled = true;
        submitEmailBtn.textContent = "Mohon tunggu...";

        const email = document.getElementById("emailInput").value.trim();

        try{
            const response = await fetch("https://api.vulnarena.space/forgot-password", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({email})
            });

            if (!response.ok) {
                showNotify("Failed", "Server error. Please try again.");
                submitEmailBtn.disabled = false;
                submitEmailBtn.textContent = "Kirim";
                return;
            }

            let result = {};

            try{
                result = await response.json(); 
            }
            catch (err){
                result = { success: false, message: "Unexpected server error" };
            }

            if (result.success && result.message == "OTP sent") {
                emailForm.classList.add('hidden');
                otpForm.classList.remove('hidden');
                document.getElementById('otpInput').value = "";
                document.getElementById('otpInput').focus();
                window.addEventListener("beforeunload", handleBeforeUnload); 
            }
            else{
            showNotify("Failed", result.message || 'something went wrong');
            submitEmailBtn.disabled = false;
            submitEmailBtn.textContent = "Reset";
            }
        }
        catch(err){
            console.error("Something went wrong: ", err);
        }
        finally{
            submitEmailBtn.disabled = false;
            submitEmailBtn.textContent = "Reset";
        }
    });


    // SUBMIT OTP 
    otpForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        submitOtpBtn.disabled = true;
        submitOtpBtn.textContent = "Mohon tunggu...";

        const otp = document.getElementById('otpInput').value.trim();

        try{
            const response = await fetch("https://api.vulnarena.space/forgot-password/otp", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({otp})
            })

            

            let result = {}

            try{
                result = await response.json(); 
            }
            catch (err){
                result = { success: false, message: "Unexpected server error" };
            }

            if(result.success && result.reset_token){
                resetToken = result.reset_token;
                otpForm.classList.add('hidden');
                passwordForm.classList.remove('hidden');
                document.getElementById('passwordInput').value = "";
                document.getElementById('passwordInput').focus();
                
            }
            else{
                document.getElementById('otpInput').value = "";
                showNotify("Failed", result.message || 'something went wrong');
                submitOtpBtn.disabled = false;
                submitOtpBtn.textContent = "Kirim";
            }
            
        }
        catch (err){
            console.error("Something went wrong: ", err);
        }
        finally{
            submitOtpBtn.disabled = false;
            submitOtpBtn.textContent = "Kirim";
        }
    });


    // SUBMIT PASSWORD 
    passwordForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        submitPasswordBtn.disabled = true;
        submitPasswordBtn.textContent = "Mohon tunggu...";

        const passwordInput = document.getElementById('passwordInput');
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');

        if(passwordInput.value !== confirmPasswordInput.value){
            showNotify("Gagal", "Password dan Konfirmasi password harus sama");
            confirmPasswordInput.value = "";
            confirmPasswordInput.focus();
            submitPasswordBtn.disabled = false;
            submitPasswordBtn.textContent = "Kirim";
            return;
        }

        const jsonData = {
            password: passwordInput.value,
            confirmPassword: confirmPasswordInput.value,
            reset_token: resetToken
        };

        try{
            const response = await fetch ("https://api.vulnarena.space/forgot-password/reset", {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(jsonData)
            });

            if (!response.ok) {
                showNotify("Failed", "Server error. Please try again.");
                submitPasswordBtn.disabled = false;
                submitPasswordBtn.textContent = "Kirim";
                return;
            }

            let result = {};

            try{
                result = await response.json();
            }
            catch(err){
                result = { success: false, message: "Unexpected server error" };
            }

            if(result.success && result.message == "Reset password success"){
                showNotify("Sukses", "Password berhasil direset! Silakan login dengan password baru");

                setTimeout(()=> { 
                    window.removeEventListener("beforeunload", handleBeforeUnload);
                    window.location.href = "/login";
                }, 2000);
            }
            else if(!result.success && result.message == "Token Expired"){
                showNotify("Gagal", "Token kadaluarsa, mohon untuk mengulangi proses");
                setTimeout(()=> { 
                    window.removeEventListener("beforeunload", handleBeforeUnload);
                    window.location.href = "/login";
                }, 2000);
            }
            else if(!result.success && result.message == "Password must be between 8 and 16 characters"){
                showNotify("Gagal", "Panjang password harus antara 8 hingga 16 karakter");
                passwordInput.value ="";
                confirmPasswordInput.value = "";
                passwordInput.focus();
                submitPasswordBtn.disabled = false;
                submitPasswordBtn.textContent = "Kirim";
            }
            else if(!result.success && result.message == "Password and confirmed password doesn't match"){
                showNotify("Gagal", "Password dan konfirmasi password harus sama");
                confirmPasswordInput.value = "";
                confirmPasswordInput.focus();
                submitPasswordBtn.disabled = false;
                submitPasswordBtn.textContent = "Kirim";
            }
            else{
                showNotify("Failed", result.message || 'something went wrong');
                submitPasswordBtn.disabled = false;
                submitPasswordBtn.textContent = "Kirim";
            }

        }
        catch (err){
            console.error("Something went wrong: ", err);
        }
        finally{
            submitPasswordBtn.disabled = false;
            submitPasswordBtn.textContent = "Kirim";
        }
    });

});