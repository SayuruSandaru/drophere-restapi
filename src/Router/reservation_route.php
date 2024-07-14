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


    $updateStatusValidation = new InputValidationMiddleware([
        'reservation_id' => 'required',
        'status' => 'required'
    ]);

    $router->post('/reservation/create', [$authMiddleware, $reservationValidation], function ($request) use ($reservationController) {
        $type = "passenger";
        $reservationController->createPassangerReservation($request, $type);
    });

    $router->post('/reservation/available', [$authMiddleware], function ($request) use ($reservationController) {
        $status = $request['status'];
        $reservationController->getAvailableReservations($status);
    });

    $router->post('/reservation/update', [$authMiddleware, $updateStatusValidation], function ($request) use ($reservationController) {
        $reservationId = $request['reservation_id'];
        $newStatus = $request['status'];
        $reservationController->updateReservationStatus($reservationId, $newStatus);
    });

    $deliveryReservationValidation = new InputValidationMiddleware([
        'driver_id' => 'required',
        'ride_id' => 'required',
        'status' => 'required',
        'price' => 'required',
        'recipient_name' => 'required',
        'recipient_address' => 'required',
        'recipient_phone' => 'required',
        'weight' => 'required',
    ]);

    $router->post('/reservation/create/delivery', [$authMiddleware, $deliveryReservationValidation], function ($request) use ($reservationController) {
        $reservationController->createDeliveryReservation($request);
    });
}
