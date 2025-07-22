<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if(!$middleware->isGetMethod()){
    $middleware->errorResponse(405,"Only GET method is allowed");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

if(!$middleware->isAuthCheck()){
    $middleware->errorResponse(401, "Unauthorized");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";
$user_id = $_SESSION['user_id'];

// get lab status
$query = "SELECT isactive, subdomain, createtime, expiredtime FROM lab WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$db->close();

// send info
if (!$row['isactive']) {
    echo json_encode([
        'success' => true,
        'active' => false
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'active' => true,
    'subdomain' => $row['subdomain'],
    'createtime' => $row['createtime'],
    'expiredtime' => $row['expiredtime']
]);

exit;
?>