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
        $token = null;

        // Case insensitive header check
        foreach ($headers as $header => $value) {
            if (strtolower($header) === 'authorization') {
                $token = $value;
                break;
            }
        }

        if ($token === null) {
            http_response_code(401);
            ResponseUtility::sendJsonResponse('error', ['message' => 'Authorization header is required'], 401);
            exit;
        }

        // Remove 'Bearer ' prefix if present
        $token = preg_replace('/^Bearer\s+/', '', $token);

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
