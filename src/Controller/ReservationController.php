<?php

namespace App\Controller;

use App\Service\ReservationService;
use App\Utility\ResponseUtility;

class ReservationController
{
    private $reservationService;

    public function __construct()
    {
        $this->reservationService = new ReservationService();
    }

    public function createPassangerReservation($request, $type)
    {
        try {
            $userId = $request['userId'];
            $driverId = $request['driver_id'];
            $rideId = $request['ride_id'];
            $status = $request['status'];
            $price = $request['price'];
            $passengerCount = $request['passenger_count'];

            $res = $this->reservationService->createReservationPassanger($userId, $driverId, $rideId, $status, $price, $passengerCount, $type);

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
                    200
                );
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $e->getMessage()],
                200
            );
        }
    }

    public function getAvailableReservations($status)
    {
        try {

            $result = $this->reservationService->getAvailableReservations($status);
            if ($result['status']) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    ['data' => $result['data']],
                    200
                );
            } else {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_ERROR,
                    ['message' => $result['message']],
                    200
                );
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => 'Failed to retrieve available reservations: ' . $e->getMessage()],
                200
            );
        }
    }


    public function updateReservationStatus($reservationId, $newStatus)
    {
        try {
            $result = $this->reservationService->updateReservationStatus($reservationId, $newStatus);
            if ($result['status']) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    ['message' => "updated reservation status successfully"],
                    200
                );
            } else {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_ERROR,
                    ['message' => $result['message']],
                    200
                );
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => 'Failed to update reservation status: ' . $e->getMessage()],
                200
            );
        }
    }
}
