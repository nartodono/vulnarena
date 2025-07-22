<?php
require_once __DIR__ . "/../../../middleware/securityHeader.php";
require_once __DIR__ . "/../../../middleware/middleware.php";
require_once __DIR__ . "/../../otp/otpCreate.php";
$middleware = new middleware;

if(!$middleware->isPostMethod()){
    $middleware->errorResponse(405, "Only POST method is allowed");
    exit;
}

if($middleware->isAuthCheck()){
    $middleware->errorResponse(403, "Already logged in");
    exit;
}

if(!$middleware->isJson()){
    $middleware->errorResponse(415, "Only application/json is accepted");
    exit;
}

if(isset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['pending_reset'])){
    unset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['pending_reset']);
}

if(isset($_SESSION['pending_reset'])){
    unset($_SESSION['pending_reset']);
}

$input = json_decode(file_get_contents("php://input"), true);
$email = trim($input['email'] ?? '');

if(empty($email)){
    $middleware->errorResponse(400, "Email cannot be empty");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $middleware->errorResponse(400, "Invalid email format");
    exit;
}

$db = require_once __DIR__ . "/../../../database/db.php";
$query = "SELECT user_id, username FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// kalo email ga ada di db, set sleep/delay 3 detik supaya attacker ga bisa nebak email benar/salah (SECURITY)
if($result->num_rows !== 1){
    sleep(3);
    echo json_encode([
        "success" => true,
        "message" => "OTP sent"
    ]);
    exit;
}

// set temporary information of reset password
$user = $result->fetch_assoc();
$_SESSION['pending_reset'] = [
    'user_id' => $user['user_id'],
    'username' => $user['username'],
    'email' => $email
];

$stmt->close();
$db->close();

// create OTP if email is exist
otp::create(2, $email, $user['username']);

exit;
?>