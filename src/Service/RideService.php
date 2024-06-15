<?php

namespace App\Service;

use App\Repository\RideRepository;

class RideService
{
    private $rideRepository;

    public function __construct()
    {
        $this->rideRepository = new RideRepository();
    }

    public function createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location)
    {
        try {
            $res =  $this->rideRepository->createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location);
            if ($res) {
                return [
                    'status' => true,
                    'message' => 'Ride created successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Error in creating ride'
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

    public function getRideById($ride_id)
    {
        try {
            $ride = $this->rideRepository->getRideById($ride_id);
            return [
                'status' => true,
                'ride' => $ride
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAllRides()
    {
        try {
            $rides = $this->rideRepository->getAllRides();
            return [
                'status' => true,
                'rides' => $rides
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
