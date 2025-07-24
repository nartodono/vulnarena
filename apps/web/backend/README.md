This directory contains all backend/API source code used to process information and handle requests from the frontend.

## Tech Stack

- **PHP (native)**: The core backend logic is written in native PHP.
- **Composer Packages**:
  - **PHPMailer**: For sending emails.
  - **K8S PHP Client**: For Kubernetes API interaction.
  - **Ratchet**: For WebSocket communication.
  > All packages are managed by Composer and stored in the `/vendor` directory.

## Directory Structure

- `/api` — Contains all application logic and data processing endpoints.
- `/config` — Configuration files, including database credentials (`dbkey.php`). Previously, this folder also stored the Cloudflare key.
- `/controller` — Manual routing scripts, used by `index.php` for request dispatching.
- `/database` — Handles the database connection (returns a new `mysqli` instance).
- `/middleware` — Middleware components for authentication, validation, and security.

## Security

This API runs **completely independent** from the frontend service, as a standalone server.  
Security features implemented include:
- SQL Prepared Statements
- CORS
- CSRF Token validation
- Session checks
- HTTP Method validation
- Content-Type checks
- Input validation
- Additional security headers (see `/middleware/securityHeader.php`)

---

Feel free to review or use this backend/API code as a reference for learning or further development.
