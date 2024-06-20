<?php

namespace App\Controller;

use App\Service\VehicleService;
use App\Utility\ResponseUtility;

class VehicleController
{
    private $vehicleService;

    public function __construct()
    {
        $this->vehicleService = new VehicleService();
    }

    public function addVehicle($request)
    {

        $owner_id = $request['userId'];
        $type = $request['type'];
        $capacity = $request['capacity'];
        $available = $request['available'];
        $licensePlate = $request['license_plate'];
        $model = $request['model'];
        $year = $request['year'];
        $image_url = $request['image_url'];
        $res = $this->vehicleService->addVehicle($owner_id, $type, $capacity, $available, $licensePlate, $model, $year, $image_url);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message'], 'id' => $res['id']],
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

    public function getVehicleById($request)
    {
        $vehicle_id = $request['vehicle_id'];

        $res = $this->vehicleService->getVehicleById($vehicle_id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['vehicle' => $res['vehicle']],
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

    public function getAllVehicles()
    {
        $res = $this->vehicleService->getAllVehicles();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['vehicles' => $res['vehicles']],
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

    public function getVehiclesByOwnerId($request)
    {
        $owner_id = $request['owner_id'];

        $res = $this->vehicleService->getVehiclesByOwnerId($owner_id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['vehicles' => $res['vehicles']],
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
}
