<?php

namespace App\Service;

use App\Repository\DriverRepository;

class DriverService
{
    private $driverRepository;

    public function __construct()
    {
        $this->driverRepository = new DriverRepository();
    }

    public function createDriver($street, $city, $province, $verificationDoc, $userId)
    {
        try {
            $res = $this->driverRepository->registerDriver($street, $city, $province, $verificationDoc, $userId);
            echo $res;
            if ($res !== 0) {
                return [
                    'status' => true,
                    'message' => 'Driver registered successfully',
                    'driver_id' => $res
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Error in registering driver'
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

    public function getDriverById($driverId)
    {
        try {
            $driver = $this->driverRepository->getDriverById($driverId);
            return [
                'status' => true,
                'driver' => $driver
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAllDrivers()
    {
        try {
            $drivers = $this->driverRepository->getAllDrivers();
            return [
                'status' => true,
                'drivers' => $drivers
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
