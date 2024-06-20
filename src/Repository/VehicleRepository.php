<?php

namespace App\Repository;

use Exception;

class VehicleRepository
{
    private $DB;

    public function __construct()
    {

        $this->DB = getDBConnection();
    }


    public function addVehicle($owner_id, $type, $capacity, $available, $licensePlate, $model, $year, $image_url)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO vehicles (owner_id, type, capacity, available, license_plate, model, year, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new \Exception("Failed to prepare statement: " . $this->DB->error);
            }
            $stmt->bind_param("isisssis", $owner_id, $type, $capacity, $available, $licensePlate, $model, $year, $image_url);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new \Exception("Error in adding vehicle");
            }
            $vehicleId = $this->DB->insert_id;
            echo $vehicleId;
            return $vehicleId;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new \Exception($e->getMessage());
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }


    public function getVehicleById($vehicle_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
            $stmt->bind_param("i", $vehicle_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Vehicle not found");
            }
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getAllVehicles()
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM vehicles");
            $stmt->execute();
            $result = $stmt->get_result();
            $vehicles = [];
            while ($row = $result->fetch_assoc()) {
                $vehicles[] = $row;
            }
            return $vehicles;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getVehiclesByOwnerId($owner_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM vehicles WHERE owner_id = ?");
            $stmt->bind_param("i", $owner_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $vehicles = [];
            while ($row = $result->fetch_assoc()) {
                $vehicles[] = $row;
            }
            return $vehicles;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
