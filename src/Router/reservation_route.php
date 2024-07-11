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

    $deliveryReservationValidation = new InputValidationMiddleware([
        'userId' => 'required', // Add userId
        'driver_id' => 'required', // Add driverId
        'service_id' => 'required',
        'recipient_name' => 'required',
        'recipient_address' => 'required',
        'recipient_phone' => 'required',
        'signature' => 'required',
        'weight' => 'required',
    ]);

    $router->post('/reservation/create/delivery', [$authMiddleware, $deliveryReservationValidation], function ($request) use ($reservationController) {
        $reservationController->createDeliveryReservation($request);
    });
}
