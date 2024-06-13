<?php

namespace App\Middleware;

use App\Utility\ResponseUtility;

class FileUploadValidationMiddleware
{
    public function handle($request, $next)
    {
        if (empty($_FILES) && empty($request)) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['error' => 'No files provided'],
                400
            );
            return false;
        }
        return $next($request);
    }
}
