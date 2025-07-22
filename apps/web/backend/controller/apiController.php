<?php

class apiController {

    //login
    public static function loginAuth(){
        require __DIR__ . "/../api/auth/loginAuth.php";
    }

    //logout
    public static function logoutAuth(){
        require __DIR__ . "/../api/auth/logoutAuth.php";
    }

    //reset pw
    public static function forgotPasswordAuth(){
        require __DIR__ . "/../api/user/reset_password/resetPassword.php";
    }

    public static function forgotPasswordOtpAuth(){
        require __DIR__ . "/../api/user/reset_password/otpResetPassword.php";
    }

    public static function resetPasswordAuth(){
        require __DIR__ . "/../api/user/reset_password/pushResetPassword.php";
    }

    //register
    public static function registerAuthCheck(){
        require __DIR__ . "/../api/auth/register/registerAuthCheck.php";
    }

    public static function registerAuthPush(){
        require __DIR__ . "/../api/auth/register/registerAuthPush.php";
    }

    //authorization check
    public static function checkAuth(){
        require __DIR__. "/../api/auth/CheckAuth.php";
    }

    //user
    public static function getUserInfo(){
        require __DIR__ . "/../api/user/getUserInfo.php";
    }

    public static function getPvpInfo(){
        require __DIR__ . "/../api/user/getPvpInfo.php";
    }

    public static function getPvpHistory(){
        require __DIR__ . "/../api/user/getPvpHistory.php";
    }

    public static function updateUserInfo(){
        require __DIR__ . "/../api/user/updateUserInfo.php";
    }

    public static function changeUserPassword(){
        require __DIR__ . "/../api/user/changeUserPassword.php";
    }

    // lab
    public static function generateLab(){
        require __DIR__ . "/../api/labs/generateLab.php";
    }

    public static function checkLab(){
        require __DIR__ . "/../api/labs/checkLab.php";
    }
    //post test
    public static function postTest(){
        require __DIR__ . "/../api/post-test/postTest.php";
    }

    //matchmaking
    public static function pvpMatchmaking(){
        require __DIR__ . "/../api/pvp/matchmaking.php";
    }
}

?>