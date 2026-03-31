<?php

namespace App\Services;

class PushService
{
    public static function send($type)
    {
        $db = db_connect();

        $tokens = $db->table('device_tokens')->get()->getResult();

        foreach ($tokens as $t) {
            self::sendToToken($t->token, $type);
        }
    }

    private static function sendToToken($token, $type)
    {
        $projectId = "fcencomiendas-15905";

        $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

        $accessToken = self::getAccessToken();

        $data = [
            "message" => [
                "token" => $token,

                "data" => [
                    "type" => $type
                ],

                "android" => [
                    "priority" => "high"
                ],

                "apns" => [
                    "payload" => [
                        "aps" => [
                            "content-available" => 1
                        ]
                    ]
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer " . $accessToken,
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);

        log_message('info', '🔥 PUSH RESPONSE: ' . $response);
    }

    private static function getAccessToken()
    {
        $keyFile = APPPATH . 'Config/firebase.json';

        $jsonKey = json_decode(file_get_contents($keyFile), true);

        $header = [
            "alg" => "RS256",
            "typ" => "JWT"
        ];

        $now = time();

        $payload = [
            "iss" => $jsonKey['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => "https://oauth2.googleapis.com/token",
            "iat" => $now,
            "exp" => $now + 3600
        ];

        $base64UrlEncode = function ($data) {
            return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
        };

        $jwtHeader = $base64UrlEncode($header);
        $jwtPayload = $base64UrlEncode($payload);

        $signatureInput = $jwtHeader . "." . $jwtPayload;

        openssl_sign($signatureInput, $signature, $jsonKey['private_key'], 'SHA256');

        $jwtSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $jwt = $jwtHeader . "." . $jwtPayload . "." . $jwtSignature;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
            "assertion" => $jwt
        ]));

        $response = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $response['access_token'];
    }
}
