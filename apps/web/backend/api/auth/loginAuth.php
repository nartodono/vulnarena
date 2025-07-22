<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
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
    $middleware->errorResponse(403, "Already logged in");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";

$input = json_decode(file_get_contents("php://input"), true);
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? null;
        
if(empty($username) || empty($password)){
    $middleware->errorResponse(400, "username or password cannot be empty");
    exit;
}

// check whether user use email or username
if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $query = "SELECT user_id, username, email, password FROM users WHERE email = ?";
} else {
    $query = "SELECT user_id, username, email, password FROM users WHERE username = ?";
}
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);

$stmt->execute();
$result = $stmt->get_result();

if($result ->num_rows !== 1){
    $middleware->errorResponse(400, "Invalid username or password");
    exit;
}

$data = $result->fetch_assoc();
$stmt->close();

// check password
if(!password_verify($password, $data['password'])){
    $middleware->errorResponse(400, "Invalid username or password");
    exit;
}
$db->close();

// set login information
$_SESSION['last_activity'] = time();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$_SESSION['user'] = $data['username'];
$_SESSION['user_email'] = $data['email'];
$_SESSION['user_id'] = $data['user_id'];

// set cookie
setcookie('csrf_token', $_SESSION['csrf_token'], [
  'path' => '/',
  'domain' => '.vulnarena.space',
  'secure' => true,
  'httponly' => false,
  'samesite' => 'None'
]);

// this cookie only used by JS for user login flag
setcookie('_ml_il', bin2hex(random_bytes(8)), [
  'path' => '/',
  'domain' => '.vulnarena.space',
  'secure' => true,
  'httponly' => false,
  'samesite' => 'None'
]);

echo json_encode([
    "success" => true,
]);
exit;
?>