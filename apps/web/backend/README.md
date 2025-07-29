This directory contains all backend/API source code used to process information and handle requests from the frontend.

## Tech Stack

- **PHP (native):** The core backend logic is written in native PHP.
- **Composer Packages:**
  - **PHPMailer:** Used to send OTP (One-Time Password) emails for registration and password recovery features.
  - **K8S PHP Client:** Used to manage dynamic lab environments in Kubernetes, allowing users to create labs and supporting automated lab deletion via Minikube cronjobs.
  - **Ratchet:** Implements the WebSocket server for the real-time PvP quiz feature.

> All packages are managed by Composer and stored in the `/vendor` directory.

## Directory Structure

- `/api` — Application logic and data processing endpoints.
- `/config` — Configuration files, including database credentials (`dbkey.php`). Previously, this folder also stored the Cloudflare key.
- `/controller` — Manual routing scripts, used by `index.php` for request dispatching.
- `/database` — Handles the database connection (returns a new `mysqli` instance).
- `/middleware` — Middleware components for authentication, validation, and security.

## Security

This API is **completely independent** from the frontend service and runs as a standalone server.  
Implemented security features include:

- SQL Prepared Statements
- CORS (Cross-Origin Resource Sharing)
- CSRF Token validation
- Session checks
- HTTP Method validation
- Content-Type checks
- Input validation
- Additional security headers (see `/middleware/securityHeader.php`)

---

Feel free to review or use this backend/API code as a reference for learning or further development.

> **Note:**  
> While this server implements several security mechanisms, it is still a prototype and **not fully secure for production use**.  
> Some recommended best practices—such as rate limiting and additional protections—are **not yet implemented**.
> Please note that the PvP feature, which uses Ratchet for real-time experience, works well as long as users follow the intended flow. However, unintended actions during PvP may cause bugs that require resetting the WebSocket memory and updating certain database information. Since this is still a prototype, there are many potential bugs that have not yet been resolved. Our priority is to ensure the PvP feature functions properly when used as intended.
