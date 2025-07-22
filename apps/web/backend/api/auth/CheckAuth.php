<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";

$middleware = new middleware();

if(!$middleware->isGetMethod()){
    $middleware->errorResponse(405, "Only GET method is allowed");
    exit;
}

if($middleware->isAuthCheck()){
    echo json_encode([
        "success" => true,
        "message" => "authorized",
        "content" => [
            "user_id" => $_SESSION['user_id'] ?? null,
            "username" => $_SESSION['user'] ?? null,
            "email" => $_SESSION['user_email'] ?? null
        ]
    ]);
    exit;
}
else{
    $expired = isset($_SESSION['session_expired']);
    if($expired) {
        session_unset();
        session_destroy();
    } else {
        $expired = false;
    }
    
    echo json_encode([
        "success" => false,
        "message" => "unauthorized",
        "expired" => $expired,
    ]);
    exit;
}
?>