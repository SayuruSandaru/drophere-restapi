<?php

namespace App\Repository;

use Exception;

require_once __DIR__ . '/../Utility/DBconfig.php';

class DriverRepository
{
    private $DB;

    public function __construct()
    {

        $this->DB = getDBConnection();
    }

    public function registerDriver($firstName, $lastName, $street, $city, $province, $verificationDoc, $status, $userId)
    {
        try {

            $stmt = $this->DB->prepare("SELECT driver_id FROM drivers WHERE driver_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                throw new Exception("Driver already registered");
            }

            $stmt = $this->DB->prepare("INSERT INTO drivers (driver_id, first_name, last_name, street, city, province, verification_doc, status) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssss", $userId, $firstName, $lastName, $street, $city, $province, $verificationDoc, $status);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in registering driver");
            }
            return true;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }


    public function getDriverById($driver_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM drivers WHERE driver_id = ?");
            $stmt->bind_param("i", $driver_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Driver not found");
            }
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getAllDrivers()
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM drivers");
            $stmt->execute();
            $result = $stmt->get_result();
            $drivers = [];
            while ($row = $result->fetch_assoc()) {
                $drivers[] = $row;
            }
            return $drivers;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
