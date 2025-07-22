<?php
// true untuk login
class authCheck{
    public function isLogin (){
        // pake internal address untuk kurangi latensi
        $response = file_get_contents('http://vulnarena-backend-service.vulnarena.svc.cluster.local/auth/check', false, stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => "Cookie: PHPSESSID=" . ($_COOKIE['PHPSESSID'] ?? '') . "\r\n"
            ]
        ]));
        
        if ($response === false) {
            return false; // gagal fetch → anggap belum login
        }
        
        $data = json_decode($response, true);
        if(!$data['success'] && $data['expired'] === true){
            return 'expired';
            exit;
        }
        return $data['success'] ?? false; // return bool
        // ini sah dan akan berhasil menyetel ulang PHPSESSID
        
    }
}
?>