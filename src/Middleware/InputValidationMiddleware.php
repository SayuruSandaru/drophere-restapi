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
            $data = [];  // Ensures $data is an array even if the JSON decoding fails
        }

        // Merge decoded data into the request array
        $request = array_merge($request, $data);

        // Perform validation
        $errors = $this->validate($request);

        // Check for validation errors
        if (!empty($errors)) {
            ResponseUtility::sendJsonResponse(['errors' => $errors], 400);
            return false;  // Stop further middleware execution and route handling
        }

        return $next($request);  // Proceed to the next middleware or the route's callback
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
