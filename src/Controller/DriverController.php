<?php

namespace App\Controller;

use App\Middleware\AuthMiddleware;
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
        $vehicleType = $request['vehicle_type'];
        $street = $request['street'];
        $city = $request['city'];
        $province = $request['province'];
        $proofDoc = $request['proof_document'];

        $res = $this->driverService->createDriver($userId, $vehicleType, $street, $city, $province, $proofDoc);

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
}
