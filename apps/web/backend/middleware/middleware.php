<?php

class phpSession {
    public static function start(){
        // set cookie and session
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', 1800);  // 30 menit
            ini_set('session.gc_probability', 1);
            ini_set('session.gc_divisor', 100);
            ini_set('session.save_path', '/var/lib/php/sessions');
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '.vulnarena.space',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'none'
            ]);
            session_start();
        }
        
        // check activity 
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > 1800) {
                // Session expired, remove session info and set flag session expired to true
                session_unset();
                $_SESSION['session_expired'] = true;
            } else {
                // Session still active, update last activity
                $_SESSION['last_activity'] = time();
            }
        }
    }
}

class middleware {
    public function __construct()
    {
        phpSession::start();
    }

    // Error response template
    public function errorResponse($statusCode, $message){
        http_response_code($statusCode);
        echo json_encode([
            "success" => false,
            "message" => $message
        ]);
    }

    // session related
    public function isAuthCheck(){
        return(isset($_SESSION['user_id']));
    }

    public function isSessionExpired(){
        return(isset($_SESSION['session_expired']) && $_SESSION['session_expired'] === true);
    }

    // HTTP Request related
    // Check if the request method is GET
    public function isGetMethod(){
        return($_SERVER['REQUEST_METHOD'] === "GET");
    }
    // Check if the request method is POST
    public function isPostMethod(){
        return($_SERVER['REQUEST_METHOD'] === "POST");
    }
    // Check content ty is JSON
    public function isJson(){
        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        return(stripos($contentType, 'application/json') === 0);
    }
    // Check csrf
    public function isCsrf(){
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        $csrfToken = $headers['x-csrf-token'] ?? '';
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $csrfToken;
    }
}
?>