<?php

namespace App\Utility;

class ResponseUtility
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    public static function sendJsonResponse($status, $data = [], $status_code = 200, $prettyPrint = true)
    {
        header('Content-Type: application/json');
        http_response_code($status_code);
        $jsonOptions = $prettyPrint ? JSON_PRETTY_PRINT : 0;

        $response = ['status' => $status] + $data;

        echo json_encode($response, $jsonOptions);
        exit;
    }
}
