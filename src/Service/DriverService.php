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

    public function createDriver($userId, $vehicleType, $street, $city, $province, $proofDoc)
    {
        try {
            $res =  $this->driverRepository->registerDriver($vehicleType, $street, $city, $province, $proofDoc, $userId);
            if ($res) {
                return [
                    'status' => true,
                    'message' => 'Driver registered successfully'
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
}
