<?php

namespace App\Controller;

use App\Service\FileUploadService;
use App\Utility\ResponseUtility;

class FileUploadController
{
    private $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $result = $this->fileUploadService->uploadFile($file);

            if (strpos($result, 'Error') !== false) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_ERROR,
                    [
                        "message" => $result
                    ],
                    500
                );
            } else {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "file uploaded sucessfully",
                        "url" => $result
                    ],
                    200
                );
            }
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                [
                    "message" => "File not found"
                ],
                400
            );
        }
    }
}
