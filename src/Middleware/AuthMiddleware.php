<?php

namespace App\Middleware;

use App\Middleware\MiddlewareBase;
use App\Service\AuthService;
use App\Utility\ResponseUtility;

class AuthMiddleware extends MiddlewareBase
{
    private $authenticationService;

    public function __construct(AuthService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function handle($request, $next)
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            ResponseUtility::sendJsonResponse('error', ['message' => 'Authorization header is required'], 401);
            exit;
        }
        $token = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $token);
        $token = trim($token);
        if (!$this->authenticationService->validateToken($token)) {
            http_response_code(401);
            ResponseUtility::sendJsonResponse('error', ['message' => 'Invalid token'], 401);
            exit;
        }
        return $next();
    }
}
