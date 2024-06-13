<?php

namespace App\Middleware;

use App\Middleware\MiddlewareBase;
use App\Service\AuthService;
use App\Utility\ResponseUtility;

class AuthMiddleware extends MiddlewareBase
{
    private $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthService();
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

        $validationResult = $this->authenticationService->validateToken($token);

        if (!$validationResult['status']) {
            http_response_code(401);
            ResponseUtility::sendJsonResponse('error', ['message' => $validationResult['message']], 401);
            exit;
        }

        $userId = $validationResult['data']['userid'] ?? null;
        if ($userId === null) {
            http_response_code(401);
            ResponseUtility::sendJsonResponse('error', ['message' => 'User ID could not be obtained'], 401);
            exit;
        }
        $request['userId'] = $userId;

        return $next($request);
    }
}
