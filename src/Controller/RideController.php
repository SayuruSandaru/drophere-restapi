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
        $status = $request['status'];
        $start_time = $request['start_time'];
        $current_location = $request['current_location'];
        $route_id = $request['route_id'];
        $start_location = $request['start_location'];
        $end_location = $request['end_location'];

        $res = $this->rideService->createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location);

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
}
