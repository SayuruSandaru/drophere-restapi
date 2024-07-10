<?php

namespace App\Service;

class JWTService
{
    // private $secretKey = "drophere-iit10-secret-key";
    // private $algorithm = "sha256";

    // public function encode(array $payload): string
    // {
    //     $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    //     $payload = json_encode($payload);

    //     $base64UrlHeader = $this->base64UrlEncode($header);
    //     $base64UrlPayload = $this->base64UrlEncode($payload);

    //     $signature = $this->generateSignature($base64UrlHeader, $base64UrlPayload, $this->secretKey);
    //     return $base64UrlHeader . "." . $base64UrlPayload . "." . $signature;
    // }

    // public function decode(string $jwt): ?array
    // {
    //     $parts = explode('.', $jwt);
    //     if (count($parts) !== 3) {
    //         return null;
    //     }

    //     list($base64UrlHeader, $base64UrlPayload, $signature) = $parts;

    //     if ($this->verifySignature($base64UrlHeader, $base64UrlPayload, $signature, $this->secretKey)) {
    //         return json_decode($this->base64UrlDecode($base64UrlPayload), true);
    //     }

    //     return null;
    // }

    // private function base64UrlEncode(string $data): string
    // {
    //     return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    // }

    // private function base64UrlDecode(string $data): string
    // {
    //     return base64_decode(strtr($data, '-_', '+/'));
    // }

    // private function generateSignature(string $header, string $payload, string $key): string
    // {
    //     return $this->base64UrlEncode(hash_hmac($this->algorithm, $header . "." . $payload, $key, true));
    // }

    // private function verifySignature(string $header, string $payload, string $signature, string $key): bool
    // {
    //     $expectedSignature = $this->generateSignature($header, $payload, $key);
    //     return hash_equals($expectedSignature, $signature);
    // }
}
