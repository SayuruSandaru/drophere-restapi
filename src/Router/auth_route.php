<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\AuthController;


function registerAuthRoutes(Router $router)
{
    $authController = new AuthController();
    $loginValidation = new InputValidationMiddleware([
        'email' => 'email',
        'password' => 'required',
    ]);

    $registerValidation = new InputValidationMiddleware([
        'email' => 'email',
        'password' => 'required',
        'firstname' => 'required',
        'lastname' => 'required',
        'username' => 'required',
        'phone' => 'required',
        'profile_image' => 'required'
    ]);

    $updateUser = new InputValidationMiddleware([
        'email' => 'email',
    ]);

    // Attach middleware to login route
    $router->post('/login', [$loginValidation], function () use ($authController) {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $authController->login($data);
        // Assume ResponseUtility::sendJsonResponse handles the response
        // ResponseUtility::sendJsonResponse($response);
    });

    // Attach middleware to register route
    $router->post('/register', [$registerValidation], function () use ($authController) {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $authController->register($data);
        // Assume ResponseUtility::sendJsonResponse handles the response
        // ResponseUtility::sendJsonResponse($response);
    });

    $router->post('/update', [$updateUser], function () use ($authController) {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $authController->updateUser($data);
    });
}
