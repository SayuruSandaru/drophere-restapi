<?php

namespace App\Controller;

use App\Service\DeliveryService;
use App\Utility\ResponseUtility;

class DeliveryController
{
    private $deliveryService;

    public function __construct()
    {
        $this->deliveryService = new DeliveryService();
    }

    public function createDelivery($request)
    {
        $userId = $request['user_id'];
        $deliveryAddress = $request['delivery_address'];
        $deliveryDate = $request['delivery_date'];
        $status = $request['status'];


        $res = $this->deliveryService->createDelivery($userId, $deliveryAddress, $deliveryDate, $status);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message'], 'delivery_id' => $res['delivery_id']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                200
            );
        }
    }

    public function getDeliveryById($request)
    {
        $deliveryId = $request['id'];

        $res = $this->deliveryService->getDeliveryById($deliveryId);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['delivery' => $res['delivery']],
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

    public function getAllDeliveries()
    {
        $res = $this->deliveryService->getAllDeliveries();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['deliveries' => $res['deliveries']],
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
