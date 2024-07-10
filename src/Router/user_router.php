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
        'email' => 'required',
        'status' => 'required',
        'phone_no' => 'required',
        'message' => 'required',
 
    ]);

    $router->post('/dispute/create', [$authMiddleware, $userValidation], function ($request) use ($userController) {
        $userController->createDispute($request);
    });
    $router->get('/dispute/{disputeId}', [$authMiddleware], function ($request, $disputeId) use ($userController) {
        $userController->getDisputeById(['dispute_id' => $disputeId]);
    });
    $router->get('/disputes', [$authMiddleware], function ($request) use ($userController) {
        $userController->getAllDisputes();
    });
    $router->post('/dispute/status', [$authMiddleware], function ($request) use ($userController) {
        $userController->updateStatus($request);
    });

}