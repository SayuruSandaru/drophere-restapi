<?php

namespace App\Middleware;

use App\Middleware\MiddlewareBase;
use App\Utility\ResponseUtility;
use AWS\CRT\HTTP\Response;

class FileUploadValidationMiddleware
{
    public function __invoke($request, $next)
    {
        // Validate the request, e.g., check if files are included in the request
        if (empty($_FILES) && empty($request)) {
            ResponseUtility::sendJsonResponse(['error' => 'No files provided'], 400);
            return false;
        }
        return $next($request);  // Proceed to the next middleware or route callback
    }
}
