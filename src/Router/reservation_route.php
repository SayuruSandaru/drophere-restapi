<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\ReservationController;
use App\Middleware\AuthMiddleware;

function registerReservationRoutes(Router $router)
{
    $reservationController = new ReservationController();
    $authMiddleware = new AuthMiddleware();
    $reservationValidation = new InputValidationMiddleware([
        'driver_id' => 'required',
        'ride_id' => 'required',
        'status' => 'required',
        'price' => 'required',
        'passenger_count' => 'required',
    ]);

    $router->post('/reservation/create', [$authMiddleware, $reservationValidation], function ($request) use ($reservationController) {
        $reservationController->createPassangerReservation($request);
    });
}
