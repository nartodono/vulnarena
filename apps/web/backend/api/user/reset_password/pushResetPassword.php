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

// validate all user input
$input = json_decode(file_get_contents("php://input"), true);

$newPassword = $input['password'] ?? '';
$confirmPassword = $input['confirmPassword'] ?? '';
$reset_token = $input['reset_token'] ?? '';

if(empty($newPassword) || empty($confirmPassword) || empty($reset_token)){
    $middleware->errorResponse(400, "Required information can not be empty or missing");
    exit;
}

$data = $_SESSION['pending_reset'] ?? null;

if(!$data){
    $middleware->errorResponse(400, "No reset password data found");
    exit;
}

if(!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_token_expire'])){
    $middleware->errorResponse(400, "No reset token found");
    exit;
}

if(time() > $_SESSION['reset_token_expire']){
    unset($_SESSION['reset_token'], $_SESSION['reset_token_expire'], $_SESSION['pending_reset']);
    $middleware->errorResponse(400, "Token Expired");
    exit;
}

if($reset_token !== $_SESSION['reset_token']){
    $middleware->errorResponse(400, "Token is invalid");
    exit;
}

if(strlen($newPassword) < 8 || strlen($newPassword) > 16){
    $middleware->errorResponse(400, "Password must be between 8 and 16 characters");
    exit;
}

if($newPassword !== $confirmPassword){
    $middleware->errorResponse(400, "Password and confirmed password doesn't match");
    exit;
}

$db = require_once __DIR__ . "/../../../database/db.php";

// get current password
$query = "SELECT password FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $_SESSION['pending_reset']['email']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows !== 1){
    $middleware->errorResponse(400, "Something went wrong. Account not found, please restart the reset password process");
    unset($_SESSION['reset_token'], $_SESSION['reset_token_expire'], $_SESSION['pending_reset']);
    exit;
}

$user = $result->fetch_assoc();

// prevent user use same password 
if(password_verify($newPassword, $user['password'])){
    $middleware->errorResponse(400, "New password must be different from current password");
    exit;
}

// update new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$query = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ss', $hashedPassword, $_SESSION['pending_reset']['email']);
$stmt->execute();
$stmt->close();
$db->close();

// remove all temporary information of reset password
unset($_SESSION['reset_token'], $_SESSION['reset_token_expire'], $_SESSION['pending_reset']);

echo json_encode([
    'success' => true,
    'message' => "Reset password success"
]);

exit;
?>