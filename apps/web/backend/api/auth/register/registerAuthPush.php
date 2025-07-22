<?php
require_once __DIR__ . "/../../../middleware/securityHeader.php";
require_once __DIR__ . "/../../../middleware/middleware.php";
$middleware = new middleware;

if(!$middleware->isPostMethod()){
    $middleware->errorResponse(405, "Only POST method is allowed");
    exit;
}

if(!$middleware->isJson()){
    $middleware->errorResponse(415, "Only application/json is accepted");
    exit;
}

if($middleware->isAuthCheck()){
    $middleware->errorResponse(403, "Already logged in. Please logout before registering a new account");
    exit;
}

$data = $_SESSION['pending_register'] ?? null;

if (!$data) {
    $middleware->errorResponse(400, "No registration data found");
    exit;
}

if(!isset( $_SESSION['reg_otp_submit']) || !isset($_SESSION['reg_otp_submit_cd'])) {
    $_SESSION['reg_otp_submit'] = 0;
    $_SESSION['reg_otp_submit_cd'] = 0;
}

if ($_SESSION['reg_otp_submit'] >= 5){
    if (time() < $_SESSION['reg_otp_submit_cd']) {
        $middleware->errorResponse(429, "Too many attempts. Try again in 30 seconds");
        exit;
    }
    else {
        $_SESSION['reg_otp_submit'] = 0;
        $_SESSION['reg_otp_submit_cd']= 0;
    }
}

if (!isset($_SESSION['reg_otp']) || !isset($_SESSION['reg_otp_expire'])) {
    $middleware->errorResponse(400, "No generated OTP found");
    exit;
}

if (time() > $_SESSION['reg_otp_expire']) {
    unset($_SESSION['reg_otp'], $_SESSION['reg_otp_expire'], $_SESSION['reg_otp_cooldown'],  $_SESSION['reg_otp_count']);
    $middleware->errorResponse(400, "OTP expired");
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$_SESSION['reg_otp_submit']++;
if ($_SESSION['reg_otp_submit'] == 5) {
    $_SESSION['reg_otp_submit_cd'] = time() + 30;
}

$otp = trim(strval($input['otp'] ?? ''));
$sessionOtp = trim(strval($_SESSION['reg_otp'] ?? ''));

if(empty($otp)){
    $middleware->errorResponse(400, "OTP code is empty or missing");
    exit;
}

if (!preg_match('/^\d{6}$/', $otp)) {
    $middleware->errorResponse(400, "Invalid OTP format");
    exit;
}

if ($otp !== $_SESSION['reg_otp']) {
    $middleware->errorResponse(400, "OTP is invalid");
    exit;
}

$email = $data['email'];
$username = $data['username'];
$password = $data['password'];

#create ID and prevent double ID, the id is always be **+******, year+random
$db = require_once __DIR__ . "/../../../database/db.php";
do {
    $yearPrefix = date('y');
    $randomPart = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $userId = intval($yearPrefix . $randomPart);

    $query = "SELECT user_id FROM users WHERE user_id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

} while ($result->num_rows !== 0);

#create nickname
$nickname = "user " . $userId;

#input data to DB
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (user_id, email, username, password) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("isss",$userId, $email, $username, $hashedPassword);
$stmt->execute();
$stmt->close();

$empty = null;
$zero = 0;

// set empty or null data for all table related to user
$query = "INSERT INTO user_profile (user_id, nickname, age, gender, bio) VALUES (?, ?, ?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("isiis", $userId, $nickname, $zero, $zero, $empty);
$stmt->execute();
$stmt->close();

$query = "INSERT INTO lab (user_id, lab_id, subdomain, pod_name, isactive, createtime, expiredtime) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("isssiii", $userId, $empty, $empty, $empty, $zero, $empty, $empty);
$stmt->execute();
$stmt->close();

$query = "INSERT INTO user_match_status (user_id, status, current_match_id) VALUES (?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("iis", $userId, $zero, $empty);
$stmt->execute();
$stmt->close();

$query = "INSERT INTO user_stats (user_id, mmr, highest_mmr, match_played, match_win, match_lost, last_match_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->bind_param("iiiiiii", $userId, $zero, $zero, $zero, $zero, $zero, $zero);
$stmt->execute();
$stmt->close();

$db->close();

unset($_SESSION['reg_otp'], $_SESSION['reg_otp_expire'], $_SESSION['pending_register'],
      $_SESSION['reg_otp_cooldown'],  $_SESSION['reg_otp_count'], 
      $_SESSION['reg_otp_submit'], $_SESSION['reg_otp_submit_cd']);

echo json_encode([
    "success" => true,
    "message" => "register success",
]);

exit;
?>