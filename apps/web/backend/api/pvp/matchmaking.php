<!-- UNUSED -->

<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

// Hanya izinkan POST
if (!$middleware->isPostMethod()) {
    $middleware->errorResponse(405, "Only POST method is allowed");
    exit;
}

if (!$middleware->isJson()) {
    $middleware->errorResponse(415, "Only application/json is accepted");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

if (!$middleware->isAuthCheck()) {
    $middleware->errorResponse(401, "You must be logged in");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";

$query = "SELECT status, current_match_id FROM user_match_status WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
if($row['status'] === '2') {
    $middleware->errorResponse(403, "You are already in a match");
    exit;
}

echo json_encode([
    "success" => true,
]);

exit;
?>