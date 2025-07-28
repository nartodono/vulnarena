# Vulnarena

**Vulnarena** is a prototype interactive website designed as an educational cybersecurity platform, originally developed as a final project (skripsi).

Vulnarena is the only open-source platform (so far) that combines:
- **Interactive learning modules and post-test**
- **Auto-generated dynamic labs for every user with automated cleanup**
- **Real-time PvP quiz (Kahoot-style) with unique 1v1 matchmaking, MMR, and detailed match history**

All features are designed for practical, hands-on cybersecurity learning—no more theory-only!  
Labs are isolated (1 pod per user), and auto-delete keeps resources clean.  
PvP battles bring the excitement of real competition and learning at once.

---

## Features

- **Learning Materials & Post-Test:**  
  Access interactive cybersecurity content, then test your overall understanding with a comprehensive post-test covering all topics.

- **Dynamic Vulnerable Labs:**  
  Practice hands-on in real, intentionally vulnerable lab environments for practical learning.

- **PvP Quiz Mode:**  
  Compete live against other users in a cybersecurity quiz game inspired by Kahoot.

- **PvP Match History & MMR:**  
  View your quiz battle history, including win/loss stats and MMR record.

- **User Account Management:**  
  Register, log in, edit your profile, change or recover your password—all essential features are included.

- **Modern UI & UX:**  
  The platform includes a clean and functional user interface and user experience, designed for effective and comfortable learning.


---

## Demo

[![Watch the demo](https://img.youtube.com/vi/hWS9AQ-bCCg/hqdefault.jpg)](https://youtu.be/hWS9AQ-bCCg)

_Watch Demonstration video on YouTube_

---

## Tech

This website is built using **HTML**, **CSS (Tailwind)**, **JavaScript**, and **native PHP**, all running inside a Minikube-based infrastructure.

The platform follows a **decoupled architecture**:  
- The user-facing website (frontend) and the backend API server are fully separated, each running on different subdomains and isolated at the pod level in Kubernetes.
- The database service is also deployed as a separate pod.

For the **dynamic lab**, each user's lab environment is fully isolated using Kubernetes namespaces. Since each lab instance is intentionally vulnerable, a dedicated pod is created per user to ensure complete isolation—so one user cannot affect another user's lab.

The codebase is written to be as clear, modular, and easy to understand as possible—especially the API, which uses native PHP with custom-built middleware and routing.

The frontend aims to provide a smooth and modern user experience, using JavaScript for interactivity and Tailwind CSS for a clean design.

### Backend API Composer Packages

The backend API uses three main Composer packages:
- **PHPMailer** — Sends OTP emails for registration and password reset.
- **K8S PHP Client** — Manages the creation and deletion of dynamic labs for users via Kubernetes (including automated cleanup using cronjobs).
- **Ratchet** — Implements the real-time WebSocket server for the PvP quiz feature.

---





---

## Project Status

This repository is released as an **open-source reference only**.  
There are **no plans for further development or maintenance**.  
The code is provided as a learning resource and example implementation for anyone interested in cybersecurity education platforms.

**Pull requests and external contributions will not be accepted.**  
Feel free to fork, study, or adapt the code for your own non-commercial learning purposes.

---

## License

MIT License

---

> **Notice:**  
> This project is for educational and reference purposes only.  
> It is **not intended for production use** and may lack certain security and scalability features.

