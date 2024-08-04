<?php

namespace App\Controller;

use App\Service\RideService;
use App\Utility\ResponseUtility;

class RideController
{
    private $rideService;

    public function __construct()
    {
        $this->rideService = new RideService();
    }

    public function createRide($request)
    {
        $driver_id = $request['driver_id'];
        $vehicle_id = $request['vehicle_id'];
        $status = $request['status'];
        $start_time = $request['start_time'];
        $current_location = $request['current_location'];
        $route_id = $request['route'];
        $start_location = $request['start_location'];
        $end_location = $request['end_location'];
        $passenger_count = $request['passenger_count'];

        $res = $this->rideService->createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location, $vehicle_id, $passenger_count);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                400
            );
        }
    }

    public function getRideById($request)
    {
        $ride_id = $request['ride_id'];

        $res = $this->rideService->getRideById($ride_id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['ride' => $res['ride']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                404
            );
        }
    }

    public function getAllRides()
    {
        $res = $this->rideService->getAllRides();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['rides' => $res['rides']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                500
            );
        }
    }

    public function getRideByStatus($request)
    {
        $status = $request['status'];

        $res = $this->rideService->getRideByStatus($status);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['rides' => $res['rides']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                404
            );
        }
    }

    public function updateRide($request)
    {
        $ride_id = $request['ride_id'];
        $status = $request['status'];

        $res = $this->rideService->updateRideStatus($ride_id, $status);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                400
            );
        }
    }

    public function deleteRide($request)
    {
        $ride_id = $request['ride_id'];

        $res = $this->rideService->deleteRide($ride_id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                400
            );
        }
    }



    public function searchRides($request)
    {
        $pickup = ['lat' => $request['pickup_lat'], 'lng' => $request['pickup_lng']];
        $destination = ['lat' => $request['destination_lat'], 'lng' => $request['destination_lng']];
        $date = $request['date'];
        $passenger_count = $request['passenger_count'];

        $suggestedRides = $this->rideService->searchRides($pickup, $destination, $date, $passenger_count);

        if (!empty($suggestedRides)) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['rides' => $suggestedRides],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['rides' => []],
                200
            );
        }
    }

    public function searchRidesByName($request)
    {
        $pickup = $request['pickup_name'];
        $destination = $request['destination_name'];
        $date = $request['date'];
        $passenger_count = $request['passenger_count'];
        $pickupCordinate = ['lat' => $request['pickup_lat'], 'lng' => $request['pickup_lng']];
        $destinationCoordinate = ['lat' => $request['destination_lat'], 'lng' => $request['destination_lng']];
        $suggestedRides = $this->rideService->searchRidesByName($pickup, $destination, $date, $passenger_count, $pickupCordinate, $destinationCoordinate);

        if (!empty($suggestedRides)) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['rides' => $suggestedRides],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['rides' => []],
                200
            );
        }
    }


    public function getDirections($request)
    {
        $pickup = ['lat' => $request['pickup_lat'], 'lng' => $request['pickup_lng']];
        $destination = ['lat' => $request['destination_lat'], 'lng' => $request['destination_lng']];

        $directions = $this->rideService->getDirections($pickup, $destination);

        if ($directions['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['directions' => $directions["directions"]],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => 'Failed to get directions'],
                500
            );
        }
    }
}
