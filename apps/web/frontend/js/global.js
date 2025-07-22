import { getCookie } from "./module.js";

document.addEventListener("DOMContentLoaded", () => {

  const desktop = document.getElementById("auth-desktop");
  const mobile = document.getElementById("auth-mobile");

  let isLoggedIn = {};
  isLoggedIn = getCookie("_ml_il") !== null;

  // HTML untuk desktop
  const desktopHTML = isLoggedIn
    ? `
      <div class="relative">
        <button id="profileToggle" class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white hover:bg-indigo-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A6.978 6.978 0 0012 20a6.978 6.978 0 006.879-5.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </button>
        <div id="profileDropdown" class="absolute right-0 mt-2 w-40 bg-gray-800 rounded-md shadow-lg opacity-0 invisible transition-all duration-300 z-10">
          <a href="/account" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Profil</a>
          <form id="logout-desktop">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Logout</button>
          </form>
        </div>
      </div>
    `
    : `
      <a href="/login">
        <button class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700">Masuk</button>
      </a>
      <a href="/register">
        <button class="px-4 py-2 text-sm font-medium text-indigo-400 border border-indigo-500 rounded hover:bg-indigo-600 hover:text-white">Daftar</button>
      </a>
    `;

  const mobileHTML = isLoggedIn
    ? `
      <a href="/account">
        <button class="w-full px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700">Profil</button>
      </a>
      <form id="logout-mobile">
        <button type="submit" class="block w-full px-4 py-2 text-sm text-red-400 hover:bg-gray-700 text-center">Logout</button>
      </form>
      `
    : `
      <a href="/login">
        <button class="w-full px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700">Masuk</button>
      </a>
      <a href="/register">
        <button class="w-full px-4 py-2 text-sm font-medium text-indigo-400 border border-indigo-500 rounded hover:bg-indigo-600 hover:text-white">Daftar</button>
      </a>
    `;

  if (desktop) desktop.innerHTML = desktopHTML;
  if (mobile) mobile.innerHTML = mobileHTML;

  // Jalankan setelah DOM updated
  setTimeout(() => {
    const toggle = document.getElementById("profileToggle");
    const dropdown = document.getElementById("profileDropdown");

    if (toggle && dropdown) {
      toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("opacity-0");
        dropdown.classList.toggle("invisible");
      });

      document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
          dropdown.classList.add("opacity-0", "invisible");
        }
      });
    }

    // Handle logout di desktop & mobile
    const logoutDesktop = document.getElementById("logout-desktop");
    const logoutMobile  = document.getElementById("logout-mobile");

    async function handleLogout(e) {
      e.preventDefault();

      const submitBtn = e.target.querySelector("button[type=submit]");
      submitBtn.disabled = true;
      submitBtn.textContent = "Loading...";

      try {
        const response = await fetch('https://api.vulnarena.space/auth/logout', {
          method: 'POST',
          credentials: "include",
          headers: {
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
          sessionStorage.clear();
          window.location.href = "/login";
        } else {
          alert(result.message || "Something went wrong");
        }
      } catch (err) {
        console.error("Logout error:", err);
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Logout";
      }
    }

    if (logoutDesktop) logoutDesktop.addEventListener("submit", handleLogout);
    if (logoutMobile)  logoutMobile.addEventListener("submit", handleLogout);

  }, 0);

});
