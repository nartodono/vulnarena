<?php

require_once __DIR__ . "/middleware.php";

class pagesController {
    private $auth;

    public function __construct() {
        $this->auth = new authCheck();
    }

    private function clearAuthCookies() {
        setcookie(session_name(), '', time() - 3600, '/', '.vulnarena.space', true, true);

        setcookie('csrf_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '.vulnarena.space',
            'secure' => true,
            'httponly' => false,
            'samesite' => 'None'
        ]);

        setcookie('_ml_il', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '.vulnarena.space',
            'secure' => true,
            'httponly' => false,
            'samesite' => 'None'
        ]);
    }

    # home page/main page
    public function homePage(){
        require __DIR__ . "/../pages/landingPage.html";
    }

    # error page
    public function errorPage(){
        require __DIR__ . "/../pages/404.html";
    }

    # login
    public function loginPage(){
        if($this->auth->isLogin()){
            header("Location: /account");
            exit;
        }
        require __DIR__ . "/../pages/login.html";
    }

    # register
    public function registerPage(){
        if($this->auth->isLogin()){
            header("Location: /account");
            exit;
        }
        require __DIR__ . "/../pages/register.html";
    }

    # reset pw
    public function forgotPasswordPage(){
        if($this->auth->isLogin()){
            header("Location: /account");
            exit;
        }
        require __DIR__ . "/../pages/forgotPassword.html";
    }

    public function learnPage(){
        require __DIR__ . "/../pages/Belajar.html";
    }

    #AFTER LOGIN
    #Play
    public function BermainPage(){
        $status = $this->auth->isLogin();
        
        if ($status === true){
            require __DIR__ . "/../pages/pvpPage.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    # PVP
    public function pvpPageDev(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/playPvp.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    #detail pvp
    public function detailPvpPage(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/detailPvpHistory.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    #history
    public function historyPage(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/historypvp.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    # account
    public function userProfilePage(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/account.html";
            exit;
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    public function changePasswordPage(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/changePassword.html";
            exit;
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    # dashboard
    public function userDashboardPage(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/dashboard.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        }
    }

    # create lab
    public function createLabPage(){
        $status = $this->auth->isLogin();
        
        if ($status === true){
            require __DIR__ . "/../pages/createLab.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        } 
    }

    #Learn Page
    #Learn Cybersecurity Introduction
    public function cyberIntroductionPage(){
        require __DIR__ . "/../pages/pengenalanCyber.html";
    }

    #Learn XSS
    public function learnXssPage(){
        require __DIR__ . "/../pages/xss.html";
    }

    #Learn Red Team
    public function learnRedPage(){
        require __DIR__ . "/../pages/Red.html";
    }

    #Learn Blue Team
    public function learnBluePage(){
        require __DIR__ . "/../pages/Blue.html";
    }

     #Learn Basic HTML
    public function learnBasicPage(){
        require __DIR__ . "/../pages/BasicHTML.html";
    }

    #Learn Broken access control
    public function learnBrokenPage(){
        require __DIR__ . "/../pages/Broken.html";
    }

    #Learn Path Traversal
    public function learnPathTraversalPage(){
        require __DIR__ . "/../pages/PathTraversal.html";
    }

    #Learn SQL Injection
    public function learnSQLInjectionPage(){
        require __DIR__ . "/../pages/SQLInjection.html";
    }

    #Learn Command Injection
    public function learnCommandInjectionPage(){
        require __DIR__ . "/../pages/CommandInjection.html";
    }

     #Profile Page
    public function Profile(){
        require __DIR__ . "/../pages/Profile.html";
    }

     #Hasil Page
    public function hasil(){
        require __DIR__ . "/../pages/hasil.html";
    }

    // #Post Test Page
    public function postTestPage(){
        $status = $this->auth->isLogin();

        if ($status === true){
            require __DIR__ . "/../pages/PostTest.html";
            exit; 
        }
        else if($status === 'expired') {
            $this->clearAuthCookies();
            header("Location: /login?expired=true");
            exit;
        }
        else{
            header("Location: /login");
            exit;
        }
    }

}
?>