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

        $res = $this->rideService->createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location, $vehicle_id);

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

    public function searchRides($request)
    {
        $pickup = ['lat' => $request['pickup_lat'], 'lng' => $request['pickup_lng']];
        $destination = ['lat' => $request['destination_lat'], 'lng' => $request['destination_lng']];

        $suggestedRides = $this->rideService->searchRides($pickup, $destination);

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
