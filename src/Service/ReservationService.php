<?php


namespace App\Service;


use App\Repository\ReservationRepository;

class ReservationService
{

    private $reservationRepository;
    public function __construct()
    {
        $this->reservationRepository = new ReservationRepository();
    }

    public function createReservationPassanger($userId, $driverId, $rideId, $status, $price, $passengerCount,)
    {
        try {
            $res =  $this->reservationRepository->createReservationPassanger($userId, $driverId, $rideId, $status, $price);
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
}
