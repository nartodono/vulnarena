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

    const form = document.getElementById("register-form");
    const otpModal = document.getElementById("otpModal");
    const otpInput = document.getElementById("otpInput");
    const verifyOtpBtn = document.getElementById("verifyOtpBtn");
    const otpResend = document.getElementById("resendOtpBtn");
    const cooldownOtpText = document.getElementById("cooldownOtpText");

    function handleBeforeUnload(e) {
      e.preventDefault();
      e.returnValue = "Verifikasi belum selesai. Keluar sekarang akan mengulang proses dari awal. Lanjutkan?";
    }

    // otp cooldown timer
    function otpCooldownShow(otpExp, otpLimit) {
      
      const otpExpTime = otpExp * 1000;
      const nowClient = Date.now();
      let countdown = Math.floor((otpExpTime - nowClient) / 1000);
      let limit = Math.floor(3 - otpLimit);
      
      document.getElementById("resendOtpText").classList.add('hidden');
      cooldownOtpText.textContent = `Kirim ulang kode OTP dalam ${countdown} detik`;
      cooldownOtpText.classList.remove("hidden");

      const timer = setInterval(() => {
        countdown--;
        cooldownOtpText.textContent = `Kirim ulang kode OTP dalam ${countdown} detik`;

        if (countdown <= 0) {
          countdown = 0;
          clearInterval(timer);
          
          cooldownOtpText.textContent = "";
          cooldownOtpText.classList.add("hidden");
          otpResend.disabled = false;
          otpResend.textContent = "Kirim ulang";
          document.getElementById("resendOtpLimit").textContent = limit;
          document.getElementById("resendOtpText").classList.remove('hidden');
        }
      }, 1000)
    }

    //send form (1st OTP)
    let otpSendTimes = 0;
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      const submitBtn = form.querySelector("button[type=submit]");
      submitBtn.disabled = true;
      submitBtn.textContent = "Mengirim...";

      const data = new FormData(form);
      const jsonData = {};
      data.forEach((value, key) => {
        jsonData[key] = value;
      });

      try {
        const response = await fetch("https://api.vulnarena.space/auth/register", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          credentials: "include",
          body: JSON.stringify(jsonData)
        });
  
        let result = {};
            
          try{
              result = await response.json(); 
          }
          catch (err){
              result = { success: false, message: "Unexpected server error" };
          }
  
        if (result.success && result.message == "OTP sent") {
            
            otpSendTimes = result.step;
            otpCooldownShow(result.otp_cooldown, otpSendTimes);
            otpModal.classList.remove("hidden");
            otpModal.classList.add("flex");

            window.addEventListener("beforeunload", handleBeforeUnload); 
            otpInput.focus();    
            return;
        }
        else{
          showNotify("Failed", result.message || 'something went wrong');
        }
  
      } catch (err) {
        console.error("Register error:", err);
        showNotify("Error", "Terjadi kesalahan. Silakan coba lagi.");
      }
      finally{
        submitBtn.disabled = false;
        submitBtn.textContent = "Daftar Sekarang";
      }
    });
  
//resend OTP
    otpResend.addEventListener("click", async() => {
      
      otpResend.disabled = true;
      otpResend.textContent = "Sending...";
      
      try{
        const response = await fetch("https://api.vulnarena.space/auth/register", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          credentials: "include",
          body: JSON.stringify({ otp: "resend" })
        })

        let result = {};
            
        try{
            result = await response.json(); 
        }
        catch (err){
            result = { success: false, message: "Unexpected server error" };
        }
  
        if (result.success && result.message == "OTP sent") {
          otpSendTimes = result.step;
          if(otpSendTimes > 2){
            otpResend.disabled = true;
            document.getElementById("resendOtpText").classList.add("hidden");
            
            cooldownOtpText.innerHTML = `
              Kode dikirim. Ini adalah pengiriman terakhir Anda.<br>
              Masih belum menerima kode OTP?
              <a href="/register" id="restartRegister" class="text-blue-600 hover:underline">Ulangi registrasi</a>
            `;
            cooldownOtpText.classList.remove("hidden");
            return;
          }
          else{
            otpCooldownShow(result.otp_cooldown, otpSendTimes);
            return;
          }
        }
        else if (!result.success && result.message == "OTP is on cooldown"){
          otpCooldownShow(result.otp_cooldown, otpSendTimes);
        }
        else{
          showNotify(result.message || 'something went wrong');
          otpInput.disabled = true;
          otpInput.classList.add("bg-gray-100", "cursor-not-allowed", "text-gray-400");
          otpInput.value = "";
          otpInput.placeholder = "Sesi berakhir";

          document.getElementById("otpInfoText").textContent = "Sesi anda telah berakhir, mohon lakukan registrasi ulang"

          document.getElementById("resendOtpBtn").disabled = true;
          document.getElementById("resendOtpText").classList.add('hidden');

          verifyOtpBtn.textContent = "Kembali";
          verifyOtpBtn.disabled = false;

          window.removeEventListener("beforeunload", handleBeforeUnload);
        }
  
      } catch (err) {
        console.error("Register error:", err);
        showNotify("Error", "Terjadi kesalahan. Silakan coba lagi.");
        
      }
      finally{
        otpResend.textContent = "kirim ulang";
      }
    });

    // Submit OTP
    verifyOtpBtn.addEventListener("click", async () => {
      
      if (verifyOtpBtn.textContent === "Kembali") {
        window.location.href = "/register";
        return;
      }

      const otp = otpInput.value.trim();
      verifyOtpBtn.disabled = true;
      verifyOtpBtn.textContent = "Mengirim...";

      if (!otp) {
        showNotify("Failed", "Masukkan OTP terlebih dahulu.");
        verifyOtpBtn.disabled = false;
        otpInput.value = "";
        verifyOtpBtn.textContent = "Verifikasi";
        return;
      }

      if (!/^\d{6}$/.test(otp)) {
        showNotify("Failed", "OTP harus berupa 6 digit angka.");
        verifyOtpBtn.disabled = false;
        otpInput.value = "";
        verifyOtpBtn.textContent = "Verifikasi";
        return;
      }
      
      try {
        const response = await fetch("https://api.vulnarena.space/auth/register/verified", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          credentials: "include",
          body: JSON.stringify({ otp })
        });
  
        let result = {};
        try{
          result = await response.json(); 
        }
        catch (err){
          result = { success: false, message: "Unexpected server error" };
        }
  
        if (result.success) {
          otpModal.classList.remove("flex");
          otpModal.classList.add("hidden");
          window.removeEventListener("beforeunload", handleBeforeUnload);
          window.location.href = "/login";
        } 
        else if(!result.success && result.message == "OTP is invalid") {
          showNotify("Failed", "Kode OTP yang anda masukkan salah.");
          otpInput.value="";

        }
        else if (
          result.message == "OTP expired" ||
          result.message == "No generated OTP found" ||
          result.message == "No registration data found"
        ) {
          showNotify("Failed", "Sesi Anda telah kedaluwarsa. Silakan registrasi ulang.");

          otpInput.disabled = true;
          otpInput.classList.add("bg-gray-100", "cursor-not-allowed", "text-gray-400");
          otpInput.value = "";
          otpInput.placeholder = "Sesi berakhir";

          document.getElementById("otpInfoText").textContent = "Sesi anda telah berakhir, mohon lakukan registrasi ulang"
          document.getElementById("resendOtpBtn").disabled = true;
          document.getElementById("resendOtpText").classList.add('hidden');

          verifyOtpBtn.textContent = "Kembali";
          verifyOtpBtn.disabled = false;

          cooldownOtpText.innerHTML = "";
          cooldownOtpText.classList.add("hidden");

          window.removeEventListener("beforeunload", handleBeforeUnload);

        }
        else{
          showNotify("Error", result.message || "Something went wrong.");
        }
      } catch (err) {
        console.error("Verifikasi OTP error:", err);
        showNotify("Error", "Terjadi kesalahan saat verifikasi OTP.");
        verifyOtpBtn.disabled = false;
        verifyOtpBtn.textContent = "Verifikasi";
      }
      finally{
        if (verifyOtpBtn.textContent !== "Kembali") {
          verifyOtpBtn.disabled = false;
          verifyOtpBtn.textContent = "Verifikasi";
        }
      }
    });
  
    // EOF
});