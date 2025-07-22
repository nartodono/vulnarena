<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";

class otp{

    public static function create($subject, $email, $username){
        $middleware = new middleware;

        date_default_timezone_set('Asia/Jakarta');
        $otp = random_int(100000, 999999);
        $timestamp = date('d M Y, H:i:s') . ' WIB';
        $time = time();

        // register otp
        if($subject === 1){
            $_SESSION['reg_otp'] = strval($otp);
            $_SESSION['reg_otp_expire'] = $time + 300; // OTP berlaku 5 menit
            $_SESSION['reg_otp_cooldown'] = $time + 60; // OTP cooldown 1 menit
        }
        // reset password otp
        else if($subject === 2){
            $_SESSION['reset_otp'] = strval($otp);
            $_SESSION['reset_otp_expire'] = $time + 300; // OTP berlaku 5 menit
        }

        // send to email
        require_once __DIR__ . '/otpSend.php';
        sendOtp::send($subject, $email, $username, $otp, $timestamp);

        if($subject === 1){
            echo json_encode([
                "success" => true,
                "step" => $_SESSION['reg_otp_count'],
                "otp_cooldown" => $_SESSION['reg_otp_cooldown'],
                "message" => "OTP sent",
            ]);
        }
        else{
            echo json_encode([
                "success" => true,
                "message" => "OTP sent"
            ]);
        }
    }
}
?>