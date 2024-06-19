<?php

namespace App\Middleware;

use App\Utility\ResponseUtility;

class InputValidationMiddleware
{
    private $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function handle($request, $next)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            $data = [];
        }


        $request = array_merge($request, $data);


        $errors = $this->validate($request);


        if (!empty($errors)) {
            ResponseUtility::sendJsonResponse(ResponseUtility::STATUS_ERROR, ['errors' => $errors], 400);
            return false;
        }

        return $next($request);
    }

    private function validate($request)
    {
        $errors = [];

        foreach ($this->rules as $field => $rule) {
            if ($rule === 'required' && empty($request[$field])) {
                $errors[$field] = $field . ' is required';
            } elseif ($rule === 'email' && isset($request[$field]) && !filter_var($request[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = $field . ' must be a valid email address';
            }
        }

        return $errors;
    }
}
