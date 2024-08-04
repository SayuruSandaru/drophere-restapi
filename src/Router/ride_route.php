<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\RideController;
use App\Middleware\AuthMiddleware;

function registerRideRoutes(Router $router)
{
    $rideController = new RideController();
    $authMiddleware = new AuthMiddleware();
    $rideValidation = new InputValidationMiddleware([
        'driver_id' => 'required',
        'vehicle_id' => 'required',
        'status' => 'required',
        'start_time' => 'required',
        'current_location' => 'required',
        'route' => 'required',
        'start_location' => 'required',
        'end_location' => 'required',
    ]);

    $directionsValidation = new InputValidationMiddleware([
        'pickup_lat' => 'required',
        'pickup_lng' => 'required',
        'destination_lat' => 'required',
        'destination_lng' => 'required',
    ]);

    $rideByStatusValidation = new InputValidationMiddleware([
        'status' => 'required',
        'ride_id' => 'required',
    ]);

    $rideDeleteValidation = new InputValidationMiddleware([
        'ride_id' => 'required',
    ]);

    $searchNameValidation = new InputValidationMiddleware([
        'pickup_name' => 'required',
        'destination_name' => 'required',
        'pickup_lat' => 'required',
        'pickup_lng' => 'required',
        'destination_lat' => 'required',
        'destination_lng' => 'required',
        'date' => 'required',
        'passenger_count' => 'required',
    ]);

    $router->post('/ride/create', [$authMiddleware, $rideValidation], function ($request) use ($rideController) {
        $rideController->createRide($request);
    });

    $router->get('/ride/{rideId}', [$authMiddleware], function ($request, $rideId) use ($rideController) {
        $rideController->getRideById(['ride_id' => $rideId]);
    });

    $router->get('/rides', [$authMiddleware], function ($request) use ($rideController) {
        $rideController->getAllRides();
    });

    $router->post('/rides/search', [$authMiddleware], function ($request) use ($rideController) {
        $rideController->searchRides($request);
    });

    $router->post('/rides/search/v1', [$authMiddleware, $searchNameValidation], function ($request) use ($rideController) {
        $rideController->searchRidesByName($request);
    });

    $router->post('/rides/direction', [$authMiddleware, $directionsValidation], function ($request) use ($rideController) {
        $rideController->getDirections($request);
    });

    $router->post('/rides/available', [$authMiddleware, $rideByStatusValidation], function ($request) use ($rideController) {
        $rideController->getRideByStatus($request);
    });

    $router->post('/rides/status', [$authMiddleware, $rideByStatusValidation], function ($request) use ($rideController) {
        $rideController->updateRide($request);
    });

    $router->post('/rides/delete', [$authMiddleware, $rideDeleteValidation], function ($request) use ($rideController) {
        $rideController->deleteRide($request);
    });
}
