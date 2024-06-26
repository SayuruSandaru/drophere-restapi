<?php

namespace App\Controller;

use App\Service\DriverService;
use App\Utility\ResponseUtility;

class DriverController
{
    private $driverService;

    public function __construct()
    {
        $this->driverService = new DriverService();
    }

    public function registerDriver($request)
    {
        $userId = $request['userId'];
        $street = $request['street'];
        $city = $request['city'];
        $province = $request['province'];
        $verificationDoc = $request['proof_document'];
        $res = $this->driverService->createDriver($street, $city, $province, $verificationDoc, $userId);
        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message'], 'driver_id' => $res['driver_id']],
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

    public function getDriverById($request)
    {
        $driverId = $request['driverId'];

        $res = $this->driverService->getDriverById($driverId);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['driver' => $res['driver']],
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

    public function getAllDrivers()
    {
        $res = $this->driverService->getAllDrivers();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['drivers' => $res['drivers']],
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

    public function getDriverByUserId($request)
    {
        $userId = $request['userId'];
        $res = $this->driverService->getDriverByUserId($userId);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['driver' => $res['driver']],
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
