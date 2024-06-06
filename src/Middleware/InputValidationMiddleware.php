<?php

namespace App\Middleware;

use App\Utility\ResponseUtility;
use App\Middleware\MiddlewareBase;

class InputValidationMiddleware extends MiddlewareBase
{
    private $rules;

    public function __construct($rules)
    {
        $this->rules = $rules;
    }

    public function handle($request, $next)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        foreach ($this->rules as $key => $rule) {
            if (!isset($data[$key])) {
                ResponseUtility::sendJsonResponse(['status' => 'error', 'message' => 'Invalid data, ' . $key . ' is required'], 400);
                return false;
            }
            if ($rule === 'email' && !filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
                ResponseUtility::sendJsonResponse(['status' => 'ailure', 'message' => $key . ' is not a valid mail'], 400);
                return false;
            }
        }

        return $next();
    }
}
