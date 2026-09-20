<?php

namespace App\Repository;

// require 'vendor/autoload.php';

class FileUploadRepository
{
    private $cloudName = "dckifek20";
private $apiKey = "893773612382249";
private $apiSecret = "4nqFZSc2EDbtNZfFTxI-Sq5OGeo";

    public function uploadFile($file)
    {
        $timestamp = time();
        $signature = sha1("timestamp={$timestamp}{$this->apiSecret}");

        $image_path = $file['tmp_name'];
        $boundary = uniqid();
        $eol = "\r\n";

        $body = "--$boundary$eol";
        $body .= "Content-Disposition: form-data; name=\"file\"; filename=\"" . basename($file['name']) . "\"$eol";
        $body .= "Content-Type: " . $file['type'] . $eol . $eol;
        $body .= file_get_contents($image_path) . $eol;
        $body .= "--$boundary$eol";
        $body .= "Content-Disposition: form-data; name=\"api_key\"$eol$eol";
        $body .= $this->apiKey . $eol;
        $body .= "--$boundary$eol";
        $body .= "Content-Disposition: form-data; name=\"timestamp\"$eol$eol";
        $body .= $timestamp . $eol;
        $body .= "--$boundary$eol";
        $body .= "Content-Disposition: form-data; name=\"signature\"$eol$eol";
        $body .= $signature . $eol;
        $body .= "--$boundary--";  // Close the body's multipart content

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: multipart/form-data; boundary={$boundary}",
                'content' => $body
            ]
        ]);

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new \Exception("Failed to upload the image");
        }

        $responseArray = json_decode($response, true);
        return $responseArray['secure_url'] ?? $responseArray['url'];
    }
}
