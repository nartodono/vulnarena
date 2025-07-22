<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware(['skipExpire' => true]);

if(!$middleware->isPostMethod()){
    $middleware->errorResponse(405, "Only POST method is allowed");
    exit;
}

// remove session
session_unset();
session_destroy();

// remove cookie
setcookie(session_name(), '', time() - 3600, '/', '.vulnarena.space', true, true);
setcookie('csrf_token', '', [
    'expires' => time() - 3600, // waktu lampau
    'path' => '/',
    'domain' => '.vulnarena.space',
    'secure' => true,
    'httponly' => false,
    'samesite' => 'None'
]);

setcookie('_ml_il', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'domain' => '.vulnarena.space',
    'secure' => true,
    'httponly' => false,
    'samesite' => 'None'
]);

echo json_encode([
    "success" => true,
    "message" => "Logout success"
]);
exit;
?>