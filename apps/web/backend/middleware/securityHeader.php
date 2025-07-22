<?php
// Basic security headers
header('Content-Type: application/json');

// CORS settings 
header('Access-Control-Allow-Credentials: true'); // Mengizinkan kirim credential (cookie)
header('Access-Control-Allow-Origin: https://vulnarena.space'); // mengizinkan hanya domain tsb melakukan cross origin req

// Extra security headers
header('X-Content-Type-Options: nosniff'); // Prevent MIME type sniffing
header('X-Frame-Options: SAMEORIGIN');     // Prevent clickjacking
header('X-XSS-Protection: 1; mode=block'); // Prevent XSS
header('Referrer-Policy: no-referrer-when-downgrade'); // Prevent include referrer when downgrade (example: HTTPS > HTTP)

// Optional: Strict Content Security Policy (CSP) 
// Uncomment this if mau pakai CSP, tapi nanti harus hati-hati test di frontend
// header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self';");
?>