<?php
require_once __DIR__ . "/../../../middleware/securityHeader.php";
require_once __DIR__ . "/../../../middleware/middleware.php";
require_once __DIR__ . "/../../otp/otpCreate.php";
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

$input = json_decode(file_get_contents("php://input"), true);

// resend otp
if(isset($input["otp"]) && $input['otp'] === "resend"){
    if(!isset($_SESSION['pending_register'])){
        $middleware->errorResponse(400, "Can't send OTP, no registration data found");
        exit;
    }

    if(time() < $_SESSION['reg_otp_cooldown']){
        $middleware->errorResponse(400, "Please wait before requesting a new OTP");
        exit;
    }

    // max otp resend is 3
    if(isset($_SESSION['reg_otp_count']) && $_SESSION['reg_otp_count'] > 3) {
        $middleware->errorResponse(400, "OTP limit reached");
        exit;
    }

    $_SESSION['reg_otp_count'] += 1;
    $data = $_SESSION['pending_register'];
    $email = $data['email'];
    $username = $data['username'];


    otp::create(1, $email, $username);
    exit;
}

// remove temporary register information if exist
if(isset($_SESSION['reg_otp'], $_SESSION['reg_otp_expire'], $_SESSION['pending_register'], $_SESSION['reg_otp_cooldown'],  $_SESSION['reg_otp_count'])){
    unset($_SESSION['reg_otp'], $_SESSION['reg_otp_expire'], $_SESSION['pending_register'], $_SESSION['reg_otp_cooldown'],  $_SESSION['reg_otp_count']);
}

$email = trim($input['email'] ?? '');
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';
$confirmPassword = $input['confirmPassword'] ?? '';

// information input validation
if (empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
    $middleware->errorResponse(400, "Required information cannot be empty or missing");
    exit;
}
// Validate username: 6-12 characters, alphanumeric only
if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $username)) {
    $middleware->errorResponse(400, "Username must be 6-12 characters and alphanumeric only");
    exit;
}
// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $middleware->errorResponse(400, "Invalid email format");
    exit;
}
// Validate password: 8-16 characters, at least one uppercase letter, one lowercase letter, one number, and one special character
if (strlen($password) < 8 || strlen($password) > 16) {
    $middleware->errorResponse(400, "Password must be between 8 and 16 characters");
    exit;
}
// Check if password contains at least one uppercase letter, one lowercase letter, one number, and one special character
if($password !== $confirmPassword){
    $middleware->errorResponse(400, "Password and confirmed password doesn't match");
    exit;
}

$db = require_once __DIR__ . "/../../../database/db.php";

# username check, prevent double username used
$query = "SELECT username FROM users WHERE username = ? LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if($result->num_rows !== 0){
    $middleware->errorResponse(400, "Username has already used");
    exit;
}

# email check, prevent double email used
$query = "SELECT email FROM users Where email = ? LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if($result->num_rows !== 0){
    $middleware->errorResponse(400, "Email has already used");
    exit;
}

// save temporary information 
$_SESSION['pending_register'] = [
    'email' => $email,
    'username' => $username,
    'password' => $password
];

// set counter for number of otp sent
if(!isset($_SESSION['reg_otp_count'])){
    $_SESSION['reg_otp_count'] = 0;
}

// OTP
otp::create(1, $email, $username);
exit;
?>