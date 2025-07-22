<?php

require_once __DIR__ . "/controller/pagesController.php";

if($_SERVER['REQUEST_METHOD'] !== "GET"){
    http_response_code(405);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    '/' => 'homePage',
    '/login' => 'loginPage',
    '/register' => 'registerPage',
    '/account' => 'userProfilePage',
    '/change-password' => 'changePasswordPage',
    '/dashboard' => 'userDashboardPage',
    '/learn' => 'learnPage',
    '/forgot-password' => 'forgotPasswordPage',
    '/learn/cyber-introduction' => 'cyberIntroductionPage',
    '/learn/xss' => 'learnXssPage',
    '/learn/red-team' => 'learnRedPage',
    '/learn/blue-team' => 'learnBluePage',
    '/learn/basic-html' => 'learnBasicPage',
    '/learn/sql-injection' => 'learnSQLInjectionPage',
    '/learn/path-traversal' => 'learnPathTraversalPage',
    '/learn/command-injection' => 'learnCommandInjectionPage',
    '/learn/broken-access-control' => 'learnBrokenPage',
    '/pvp' => 'BermainPage',
    '/pvp/play' => 'pvpPageDev',
    '/pvp/history' => 'historyPage',
    '/labs' => 'createLabPage',
    '/post-test' => 'postTestPage',
    '/hasiltest' => 'detailPvpPage'
];

$controller = new pagesController();
if (isset($routes[$uri])) {
    $method = $routes[$uri];
    $controller->$method();
    exit;
}
else{
    $controller->errorPage();
    exit;
}

?>