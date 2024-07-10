<?php

namespace App\Middleware;

use App\Middleware\MiddlewareBase;
use App\Utility\ResponseUtility;

class AuthMiddleware extends MiddlewareBase
{
    public function handle($request, $next)
    {

        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            http_response_code(401);
            ResponseUtility::sendJsonResponse(ResponseUtility::STATUS_ERROR, ['message' => 'Unauthorized: Please log in'], 401);
            exit;
        }

        $request['userId'] = $_SESSION['user_id'];

        return $next($request);
    }
}
