<?php
require_once __DIR__ . "/../../middleware/middleware.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

class sendOtp {

    public static function send($subject, $email, $username, $otp, $timestamp) {
        $subjectList = [
            '1' => 'Vulnarena - Kode OTP Registrasi',
            '2' => 'Vulnarena - Kode OTP Reset Password'
        ];

        $mail = new PHPMailer(true);

        try {
            // Konfigurasi SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = ''; // your google app email
            $mail->Password   = ''; // your google app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email pengirim dan penerima
            $mail->setFrom('cyberian.xyz69@gmail.com', 'Vulnarena');
            $mail->addAddress($email, $username);

            // Konten email
            $mail->isHTML(true);
            $mail->Subject = $subjectList[$subject];
            $mail->Body ="
                            <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6;'>
                            <p style='margin: 0 0 10px; color: #333;'><strong style='color: #333;'>Hai " . htmlspecialchars($username) . "</strong>,</p>

                            <p style='margin: 0 0 10px; color: #333;'>
                                Berikut adalah kode OTP Anda. Kode ini berlaku selama <strong style='color: #333;'>5 menit</strong>:
                            </p>

                            <p style='font-size: 24px; font-weight: bold; color: #000; margin: 20px 0;'>
                                $otp
                            </p>

                            <p style='margin: 0 0 10px; color: #333;'>
                                <strong style='color: #333;'>Kode ini bersifat rahasia.</strong> Mohon jangan membagikan kode OTP ini kepada siapa pun.
                            </p>

                            <p style='margin: 20px 0 10px; color: #333;'>
                                Jika Anda tidak meminta kode ini, Anda bisa mengabaikan email ini.
                            </p>

                            <hr style='margin: 20px 0; border: none; border-top: 1px solid #ccc;'>

                            <p style='font-size: 12px; color: #888;'>
                                Vulnarena Cybersecurity System – powered by community & curiosity.<br>
                                $timestamp
                            </p>
                            </div>
                        ";

            $mail->send();

            return true;

        } catch (Exception $e) {
            error_log("OTP Email gagal: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>