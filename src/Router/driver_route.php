<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\DriverController;
use App\Middleware\AuthMiddleware;

function registerDriverRoutes(Router $router)
{
    $driverController = new DriverController();
    $authMiddleware = new AuthMiddleware();
    $driverValidation = new InputValidationMiddleware([
        'vehicle_type' => 'required',
        'street' => 'required',
        'city' => 'required',
        'province' => 'required',
        'proof_document' => 'required',
    ]);

    $router->post('/driver/register', [$authMiddleware, $driverValidation], function ($request) use ($driverController) {
        // Pass the request directly to the controller method
        $driverController->registerDriver($request);
    });
}
