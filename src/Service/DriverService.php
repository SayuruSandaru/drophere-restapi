<?php

namespace App\Service;

use App\Repository\DriverRepository;
use App\Repository\UserRepository;

class DriverService
{
    private $driverRepository;
    private $userRepository;

    public function __construct()
    {
        $this->driverRepository = new DriverRepository();
        $this->userRepository = new UserRepository();
    }

    public function createDriver($street, $city, $province, $verificationDoc, $userId)
    {
        try {
            $res = $this->driverRepository->registerDriver($street, $city, $province, $verificationDoc, $userId);
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
            if (!$driver) {
                return [
                    'status' => false,
                    'message' => 'Driver not found'
                ];
            } else {
                $user = $this->userRepository->findById($driver['user_id']);
                $driver['user'] = $user;
                return [
                    'status' => true,
                    'driver' => $driver
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

    public function getDriverByUserId($userId)
    {
        try {
            $driver = $this->driverRepository->getDriverByUserId($userId);
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
}
