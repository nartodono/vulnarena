<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: https://vulnarena.space');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
    header('Access-Control-Allow-Credentials: true');
    http_response_code(204);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

require_once __DIR__ . "/controller/apiController.php";
$routes = [
    '/auth/login' => 'loginAuth',
    '/auth/logout' => 'logoutAuth',
    '/auth/register' => 'registerAuthCheck',
    '/auth/register/verified' => 'registerAuthPush',
    '/auth/check' => 'checkAuth',
    '/user' => 'getUserInfo',
    '/user/update' => "updateUserInfo",
    '/user/change-password' => 'changeUserPassword',
    '/user/pvp/stat' => 'getPvpInfo',
    '/user/pvp/history' => 'getPvpHistory',
    '/forgot-password' => "forgotPasswordAuth",
    '/forgot-password/otp' => "forgotPasswordOtpAuth",
    '/forgot-password/reset' => "resetPasswordAuth",
    '/lab/create' => 'generateLab',
    '/lab/status' => 'checkLab',
    '/post-test/result' => 'postTest',
    '/play' => 'pvpMatchmaking',
];

if(isset($routes[$uri])){
    apiController::{$routes[$uri]}();
    exit;
}
else{
    require __DIR__ . "/other.php";
    exit;
}

?>