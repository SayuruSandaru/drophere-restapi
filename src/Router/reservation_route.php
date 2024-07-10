<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\ReservationController;
use App\Middleware\AuthMiddleware;

function reservationRoutes(Router $router)
{
    $reservationController = new ReservationController();
    $authMiddleware = new AuthMiddleware();
    $driverValidation = new InputValidationMiddleware([
        'driver_id' => 'required',
        'ride_id' => 'required',
        'status' => 'required',
        'price' => 'required',
    ]);

    $router->post('/reservation/create', [$authMiddleware, $driverValidation], function ($request) use ($reservationController) {
        $reservationController->createPassangerReservation($request);
    });
}
