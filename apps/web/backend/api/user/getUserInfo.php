<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if(!$middleware->isGetMethod()){
    $middleware->errorResponse(405, "Only GET method is allowed");
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

$db = require_once __DIR__ . "/../../database/db.php";
$user_id = $_SESSION['user_id'];

// get user profile info
$query = "
    SELECT 
    users.email,
    users.username,
    users.user_id,
    user_profile.nickname,
    user_profile.gender,
    user_profile.age,
    user_profile.bio
    FROM users
    LEFT JOIN user_profile ON users.user_id = user_profile.user_id
    WHERE users.user_id = ?
";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$stmt->close();
$db->close();

echo json_encode([
    "success" => true,
    "data" => $row
]);
exit;

?>