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
        'first_name' => 'required',
        'last_name' => 'required',
        'street' => 'required',
        'city' => 'required',
        'province' => 'required',
        'verification_doc' => 'required',
        'status' => 'required',
    ]);

    $router->post('/driver/register', [$authMiddleware, $driverValidation], function ($request) use ($driverController) {
        $driverController->registerDriver($request);
    });

    $router->get('/driver/{driverId}', [$authMiddleware], function ($request, $driverId) use ($driverController) {
        $driverController->getDriverById(['driverId' => $driverId]);
    });

    $router->get('/drivers', [$authMiddleware], function ($request) use ($driverController) {
        $driverController->getAllDrivers();
    });
}
