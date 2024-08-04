<?php

namespace App\Service;

use App\Repository\ReservationRepository;
use App\Repository\RideRepository;

class ReservationService
{
    private $reservationRepository;
    private $riderepository;
    public function __construct()
    {
        $this->riderepository = new RideRepository();
        $this->reservationRepository = new ReservationRepository();
    }

    public function createReservationPassanger($userId, $driverId, $rideId, $status, $price, $passengerCount, $type)
    {
        try {
            $res =  $this->reservationRepository->createReservationPassenger($userId, $driverId, $rideId, $status, $price, $type);
            if ($res > 0) {
                $res2 = $this->reservationRepository->createPassengerService($passengerCount, $res);
                if ($res2 > 0) {
                    return [
                        'status' => true,
                        'message' => 'Reservation created successfully'
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => 'Error in creating passenger service entry'
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Error in creating reservation'
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAvailableReservations($status, $userId)
    {
        try {
            $reservations = $this->reservationRepository->getAvailableReservations($status, $userId);
            if (count($reservations) == 0) {
                return [
                    'status' => false,
                    'message' => 'No available reservations found'
                ];
            }

            foreach ($reservations as $key => $reservation) {
                $rideDetails = $this->riderepository->getRideById($reservation['ride_id']);
                $reservations[$key]['ride'] = $rideDetails;
            }

            return [
                'status' => true,
                'data' => $reservations
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to retrieve available reservations: ' . $e->getMessage()
            ];
        }
    }

    public function updateReservationStatus($reservationId, $newStatus)
    {
        try {
            $result = $this->reservationRepository->updateReservationStatus($reservationId, $newStatus);
            if ($result) {
                return [
                    'status' => true,
                    'message' => 'Reservation status updated successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Failed to update reservation status'
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to update reservation status: ' . $e->getMessage()
            ];
        }
    }



    public function createDeliveryReservation($userId, $driverId, $rideId, $status, $price, $recipientName, $recipientAddress, $recipientPhone, $weight, $type)
    {
        try {
            $res = $this->reservationRepository->createReservationPassenger($userId, $driverId, $rideId, $status, $price, $type);
            if ($res > 0) {
                $res2 = $this->reservationRepository->createDeliveryService($recipientName, $recipientAddress, $recipientPhone, $weight);
                if ($res2 > 0) {
                    return [
                        'status' => true,
                        'message' => 'Reservation created successfully'
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => 'Error in creating delivery service entry'
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Error in creating reservation'
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
