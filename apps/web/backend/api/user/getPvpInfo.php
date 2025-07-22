<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if (!$middleware->isGetMethod()) {
    $middleware->errorResponse(405, "Only POST method is allowed");
    exit;
}

if (!$middleware->isAuthCheck()) {
    $middleware->errorResponse(401, "You must be logged in");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";
$user_id = $_SESSION['user_id'];

// get user stat for pvp
$query = "SELECT mmr, highest_mmr, match_played, match_win, match_lost, last_match_id FROM user_stats WHERE user_id = ?";
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


?>