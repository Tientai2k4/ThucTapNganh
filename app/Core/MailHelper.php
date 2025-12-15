<?php
namespace App\Core;

class MailHelper {
    
    private static $senderEmail = 'dat09269@gmail.com'; 
    private static $senderName = 'Swimming Store';

    public static function send($toEmail, $subject, $htmlContent) {
        // Lấy API Key từ hằng số toàn cục đã được load từ config.php
        $apiKey = SENDGRID_API_KEY; // Sử dụng hằng số

        // Nếu API Key rỗng (chưa cấu hình), tránh gọi cURL
        if (empty($apiKey)) {
            return false; 
        }

        $url = 'https://api.sendgrid.com/v3/mail/send';
        
        $data = [
            "personalizations" => [
                [
                    "to" => [
                        ["email" => $toEmail]
                    ],
                    "subject" => $subject
                ]
            ],
            "from" => [
                "email" => self::$senderEmail,
                "name" => self::$senderName
            ],
            "content" => [
                [
                    "type" => "text/html",
                    "value" => $htmlContent
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            // SỬ DỤNG $apiKey ĐƯỢC GÁN TỪ HẰNG SỐ
            'Authorization: Bearer ' . $apiKey, 
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode == 202; 
    }
}