<?php

namespace App\Service;

use App\Repository\VehicleRepository;

class VehicleService
{
    private $vehicleRepository;

    public function __construct()
    {
        $this->vehicleRepository = new VehicleRepository();
    }

    public function addVehicle($owner_id, $type, $capacity, $available, $licensePlate, $model, $year, $image_url)
    {
        try {
            $vehicleId = $this->vehicleRepository->addVehicle($owner_id, $type, $capacity, $available, $licensePlate, $model, $year, $image_url);
            if ($vehicleId > 0) {
                return [
                    'status' => true,
                    'message' => 'Vehicle added successfully',
                    'id' => $vehicleId
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Failed to add vehicle',
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

    public function getVehicleById($vehicle_id)
    {
        try {
            $vehicle = $this->vehicleRepository->getVehicleById($vehicle_id);
            return [
                'status' => true,
                'vehicle' => $vehicle
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAllVehicles()
    {
        try {
            $vehicles = $this->vehicleRepository->getAllVehicles();
            return [
                'status' => true,
                'vehicles' => $vehicles
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getVehiclesByOwnerId($owner_id)
    {
        try {
            $vehicles = $this->vehicleRepository->getVehiclesByOwnerId($owner_id);
            return [
                'status' => true,
                'vehicles' => $vehicles
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
