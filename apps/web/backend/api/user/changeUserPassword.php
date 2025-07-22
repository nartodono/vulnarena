<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if(!$middleware->isPostMethod()){
    $middleware->errorResponse(405,"Only POST method is allowed");
    exit;
}

if(!$middleware->isJson()){
    $middleware->errorResponse(415, "Only application/json is accepted");
    exit;
}

if(!$middleware->isAuthCheck()){
    $middleware->errorResponse(401, "Unauthorized");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

if(!$middleware->isCsrf()){
    $middleware->errorResponse(403, "Invalid CSRF token");
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

$currentPassword = $input['currentPassword'] ?? null;
$newPassword = $input['newPassword'] ?? null;
$confirmPassword = $input['confirmPassword'] ?? null;

if(empty($currentPassword) || empty($newPassword) || empty($confirmPassword)){
    $middleware->errorResponse(400, "Required information can not be empty or missing");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";

$query = "SELECT password FROM users WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// check user input
if(!password_verify($currentPassword, $user['password'])){
    $middleware->errorResponse(400, "Wrong current password");
    exit;
}

if(strlen($newPassword) < 8 || strlen($newPassword) > 16){
    $middleware->errorResponse(400, "Password must be between 8 and 16 characters");
    exit;
}

if($newPassword === $currentPassword){
    $middleware->errorResponse(400, "New password must be different from current password");
    exit;
}

if($newPassword !== $confirmPassword){
    $middleware->errorResponse(400, "Password and confirmed password doesn't match");
    exit;
}

// update new password to db
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$query = "UPDATE users SET password = ? WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('si', $hashedPassword, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$db->close();

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
    'success' => true,
    'message' => "Change password success"
]);

exit;
?>