import { getUserInfo } from "./module.js";
import { getCookie } from "./module.js";

let emailReal = ""; // Untuk menyimpan email asli secara global

function showToast(message) {
  const toast = document.getElementById("toast");
  document.getElementById("toastMessage").textContent = message;
  toast.classList.remove("opacity-0", "pointer-events-none");
  toast.classList.add("opacity-100");

  setTimeout(() => {
    toast.classList.remove("opacity-100");
    toast.classList.add("opacity-0");
    // Optional: tunggu sampai animasi selesai (300ms), lalu pointer-events-none
    setTimeout(() => {
      toast.classList.add("pointer-events-none");
    }, 300);
  }, 2000);
}


export function getGreeting(hour = new Date().getHours()) {
  if (hour >= 0 && hour < 11) return "Selamat pagi";
  if (hour >= 11 && hour < 15) return "Selamat siang";
  if (hour >= 15 && hour < 18) return "Selamat sore";
  return "Selamat malam";
}

export function showGreeting(nickname) {
  const greeting = getGreeting();
  const greetingEl = document.getElementById("greeting");
  if (greetingEl) {
    greetingEl.textContent = `${greeting}, ${nickname}!`;
  }
}

function maskEmail(email) {
  const [user, domain] = email.split("@");
  const maskedUser = user.length > 2 ? user.slice(0, 2) + "****" : "****";
  const maskedDomain = domain.replace(/[^@.]/g, "*");
  return maskedUser + "@" + maskedDomain;
}

document.addEventListener("DOMContentLoaded", () => {
  async function loadProfile() {
    let user = null;
    const raw = sessionStorage.getItem("user_info");
    try {
      if (raw) user = JSON.parse(raw);
    } catch (err) {
      console.error("Failed to parse session data:", err);
    }
    if (!user) {
      try {
        user = await getUserInfo();
      } catch (err) {
        alert("Failed to fetch user info.");
        return;
      }
    }

    document.getElementById("user_id").textContent = user.user_id;
    document.getElementById("nickname").textContent = user.nickname;
    showGreeting(user.nickname);
    emailReal = user.email;
    document.getElementById("email").textContent = maskEmail(emailReal);

    const toggleBtn = document.getElementById("toggleEmail");
    if (toggleBtn) {
      toggleBtn.textContent = "Tampilkan";
    }

    document.getElementById("age").textContent = (user.age === 0) ? "Hidden" : user.age;

    const genderMap = { 0: "Hidden", 1: "Boy", 2: "Girl" };
    document.getElementById("gender").textContent = genderMap[user.gender];
    document.getElementById("bio").textContent = user.bio || "Not set";

    // Prefill edit form
    document.getElementById("form-user-id").value = user.user_id;
    document.getElementById("form-nickname").value = user.nickname;
    document.getElementById("form-age").value = user.age ?? 0;
    document.getElementById("form-gender").value = user.gender ?? 0;
    document.getElementById("form-bio").value = user.bio || "";
  }
  loadProfile();

  document.addEventListener("click", (e) => {
    if (e.target && e.target.id === "toggleEmail") {
      const emailEl = document.getElementById("email");
      if (e.target.textContent === "Tampilkan") {
        emailEl.textContent = emailReal;
        e.target.textContent = "Sembunyikan";
      } else {
        emailEl.textContent = maskEmail(emailReal);
        e.target.textContent = "Tampilkan";
      }
    }
  });

  document.getElementById("edit-btn").addEventListener("click", () => {
    document.getElementById("profile").style.display = "none";
    document.getElementById("edit-btn").style.display = "none";
    document.getElementById("edit-pw-btn").style.display = "none";
    document.getElementById("edit-form").style.display = "block";
    
  });

  document.getElementById("cancel-edit").addEventListener("click", async(e) => {
    e.preventDefault();
    document.getElementById("profile").style.display = "block";
    document.getElementById("edit-btn").style.display = "block";
    document.getElementById("edit-pw-btn").style.display = "block";
    document.getElementById("edit-form").style.display = "none";
  });

  document.getElementById("edit-form").addEventListener("submit", async (e) => {
    e.preventDefault();
    const submitBtn = document.getElementById("submit-edit");
    const cancelBtn = document.getElementById("cancel-edit");
    cancelBtn.disabled = true;
    submitBtn.disabled = true;
    submitBtn.textContent = "Loading...";

    let user = null;
    const raw = sessionStorage.getItem("user_info");
    try {
      if (raw) user = JSON.parse(raw);
    } catch (err) {
      console.error("Invalid JSON in sessionStorage:", err);
      user = null;
    }
    if (!user) {
      alert("User data is missing or corrupted.");
      return;
    }

    const input = {
      nickname: document.getElementById("form-nickname").value.trim(),
      age: parseInt(document.getElementById("form-age").value),
      gender: parseInt(document.getElementById("form-gender").value),
      bio: document.getElementById("form-bio").value.trim()
    }

    const normalizedInputBio = input.bio === "" ? null : input.bio;
    const normalizedSessionBio = user.bio === "" ? null : user.bio;
    const noChange = (
      input.nickname === user.nickname &&
      input.age === user.age &&
      input.gender === user.gender &&
      normalizedInputBio === normalizedSessionBio
    );

    if (noChange) {
      showToast("Perubahan tidak ditemukan");
      submitBtn.disabled = false;
      cancelBtn.disabled = false;
      submitBtn.textContent = "Simpan";
      document.getElementById("profile").style.display = "block";
      document.getElementById("edit-btn").style.display = "block";
      document.getElementById("edit-pw-btn").style.display = "block";
      document.getElementById("edit-form").style.display = "none";
      return;
    }

    if(input.nickname != user.nickname){
      if(input.nickname.length < 6 || input.nickname.length > 12 ){
        alert("nickname can only containing 6-12 characters");
        submitBtn.disabled = false;
        submitBtn.textContent = "Simpan";
        return;
      }
    }

    if(input.age != user.age){
      if(isNaN(input.age) || input.age < 0 || input.age > 99){
        alert("Please enter a valid age between 0 and 99.");
        submitBtn.disabled = false;
        submitBtn.textContent = "Simpan";
        return;
      }
    }

    if(input.gender != user.gender){
      const validGenders = [0,1,2];
      if(!validGenders.includes(input.gender)){
        alert("Invalid gender selected");
        submitBtn.disabled = false;
        submitBtn.textContent = "Simpan";
        return;
      }
    }

    if (normalizedInputBio !== normalizedSessionBio) {
      if (input.bio.length > 250) {
        alert("maximum bio length is 250 characters");
        submitBtn.disabled = false;
        submitBtn.textContent = "Simpan";
        return;
      }
    }

    try {
      const response = await fetch("https://api.vulnarena.space/user/update",{
        method: "POST",
        credentials: "include",
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token' : getCookie("csrf_token") || ""
        },
        body: JSON.stringify(input)
      });

      let result = {};
      try {
        result = await response.json(); 
      } catch (err){
        result = { success: false, message: "Unexpected server error" };
      }

      if(result.success){
        try{
          await getUserInfo();
          loadProfile();
          showToast("Update profil berhasil");
        } catch (err){
          console.error("error while fetching data:", err);
        }
        document.getElementById("profile").style.display = "block";
        document.getElementById("edit-btn").style.display = "block";
        document.getElementById("edit-pw-btn").style.display = "block";
        document.getElementById("edit-form").style.display = "none";
      } else if (result.message?.trim().toLowerCase() === "session expired") {
          window.location.href = "/account";
      } else{
          showToast(result.message || "something went wrong");
      }
    } catch (err){
      console.error("Failed to fetch data:", err);
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = "Simpan";
      cancelBtn.disabled = false;
    }
  });

});