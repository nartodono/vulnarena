<?PHP
require_once __DIR__ . "/../../../middleware/securityHeader.php";
require_once __DIR__ . "/../../../middleware/middleware.php";
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

// validate all otp status
$data = $_SESSION['pending_reset'] ?? null;

if(!$data){
    $middleware->errorResponse(400, "No reset password data found");
    exit;
}

if(!isset( $_SESSION['reset_otp_submit']) || !isset($_SESSION['reset_otp_submit_cd'])) {
    $_SESSION['reset_otp_submit'] = 0;
    $_SESSION['reset_otp_submit_cd'] = 0;
}

if ($_SESSION['reset_otp_submit'] >= 5){
    if (time() < $_SESSION['reset_otp_submit_cd']) {
        $middleware->errorResponse(429, "Too many attempts. Try again in 30 seconds");
        exit;
    }
    else {
        $_SESSION['reset_otp_submit'] = 0;
        $_SESSION['reset_otp_submit_cd']= 0;
    }
}

if(!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_otp_expire'])){
    $middleware->errorResponse(400, "No generated OTP found");
    exit;
}

if(time() > $_SESSION['reset_otp_expire']){
    unset($_SESSION['reset_otp'], $_SESSION['reset_otp_expire']);
    $middleware->errorResponse(400, "OTP expired");
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$otp = trim(strval($input['otp'] ?? ''));
$_SESSION['reset_otp_submit']++;
if ($_SESSION['reset_otp_submit'] == 5) {
    $_SESSION['reset_otp_submit_cd'] = time() + 30;
}

if (!preg_match('/^\d{6}$/', $otp)) {
    $middleware->errorResponse(400, "Invalid OTP format");
    exit;
}

if ($otp !== $_SESSION['reset_otp']) {
    $middleware->errorResponse(400, "OTP is invalid");
    exit;
}

// unser OTP if correct (already used)
unset($_SESSION['reset_otp'], $_SESSION['reset_otp_expire']);

$_SESSION['reset_token'] = bin2hex(random_bytes(32));
$_SESSION['reset_token_expire'] = time() + 600;

echo json_encode([
    "success" => true,
    "message" => "OTP is correct",
    "reset_token" => $_SESSION['reset_token']
]);

exit;
?>