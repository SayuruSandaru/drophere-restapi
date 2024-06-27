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


    public function createRide($driver_id, $status, $start_time, $current_location, $route, $start_location, $end_location, $vehicle_id)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO ride (driver_id, status, start_time, current_location, route, start_location, end_location, vehicle_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
            }
            $stmt->bind_param("issssssi", $driver_id, $status, $start_time, $current_location, $route, $start_location, $end_location, $vehicle_id);
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

    public function searchRides($pickup, $destination)
    {
        try {
            $rides = $this->getAllRides();
            $suggestedRides = [];

            foreach ($rides as $ride) {
                $routePoints = $this->decodePolyline($ride['route']);
                if ($this->isPointOnRoute($pickup, $routePoints) && $this->isPointOnRoute($destination, $routePoints)) {
                    $suggestedRides[] = $ride;
                }
            }

            return $suggestedRides;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error searching for rides: " . $e->getMessage());
        }
    }

    private function decodePolyline($encoded)
    {
        $length = strlen($encoded);
        $index = 0;
        $points = [];
        $lat = 0;
        $lng = 0;

        while ($index < $length) {
            $b = 0;
            $shift = 0;
            $result = 0;
            do {
                $b = ord(substr($encoded, $index++)) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = 0;
            $result = 0;
            do {
                $b = ord(substr($encoded, $index++)) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $points[] = ['lat' => $lat * 1e-5, 'lng' => $lng * 1e-5];
        }

        return $points;
    }

    private function isPointOnRoute($point, $route, $tolerance = 0.01)
    {
        foreach ($route as $routePoint) {
            $distance = $this->haversineDistance($point, $routePoint);
            if ($distance <= $tolerance) {
                return true;
            }
        }
        return false;
    }

    private function haversineDistance($point1, $point2)
    {
        $earthRadius = 6371000; // meters
        $lat1 = deg2rad($point1['lat']);
        $lon1 = deg2rad($point1['lng']);
        $lat2 = deg2rad($point2['lat']);
        $lon2 = deg2rad($point2['lng']);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
