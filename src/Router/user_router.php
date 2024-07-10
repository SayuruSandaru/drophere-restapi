<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;

function registerUserRoutes(Router $router)
{
    // Instantiate the necessary controller and middleware
    $userController = new UserController();
    $authMiddleware = new AuthMiddleware();
    $rideValidation = new InputValidationMiddleware([
        'category' => 'required',
        'status' => 'required',
        'message' => 'required',

    ]);

    $router->post('/user/dispute/create', [$authMiddleware, $rideValidation], function ($request) use ($userController) {
        $userController->createDispute($request);
    });

    $router->get('/user/dispute/{disputeId}', [$authMiddleware], function ($request, $disputeId) use ($userController) {
        $userController->getDisputeById($disputeId);
    });
    $router->get('/user/disputes', [$authMiddleware], function ($request) use ($userController) {
        $userController->getAllDisputes();
    });
    $router->post('/user/dispute/status', [$authMiddleware], function ($request) use ($userController) {
        $userController->updateStatus($request);
    });
}
