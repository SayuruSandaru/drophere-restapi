<?php

namespace App\Repository;

use Exception;

class RideRepository
{
    private $DB;

    public function __construct()
    {

        $this->DB = getDBConnection();
    }



    public function createRide($driver_id, $status, $start_time, $current_location, $route, $start_location, $end_location)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO ride (driver_id, status, start_time, current_location, route, start_location, end_location) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
            }
            $stmt->bind_param("issssss", $driver_id, $status, $start_time, $current_location, $route, $start_location, $end_location);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating ride: No rows affected.");
            }
            $id = $this->DB->insert_id;
            return true;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("General error: " . $e->getMessage());
        }
    }


    public function getAllRides()
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM ride");
            $stmt->execute();
            $result = $stmt->get_result();
            $rides = [];
            while ($row = $result->fetch_assoc()) {
                $rides[] = $row;
            }
            return $rides;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getRideById($ride_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM ride WHERE ride_id = ?");
            $stmt->bind_param("i", $ride_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Ride not found");
            }
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
