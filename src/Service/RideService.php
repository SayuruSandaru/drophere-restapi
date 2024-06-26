<?php

namespace App\Service;

use App\Repository\RideRepository;
use App\Repository\DriverRepository;
use App\Repository\VehicleRepository;

class RideService
{
    private $rideRepository;
    private $vehicleRepository;
    private $driverRepository;

    public function __construct()
    {
        $this->rideRepository = new RideRepository();
        $this->driverRepository = new DriverRepository();
        $this->vehicleRepository = new VehicleRepository();
    }

    public function createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location, $vehicle_id)
    {
        try {
            $res =  $this->rideRepository->createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location, $vehicle_id);
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

    public function searchRides($pickup, $destination)
    {
        $rides = $this->rideRepository->getAllRides();
        $suggestedRides = [];

        foreach ($rides as $ride) {
            $routePoints = $this->decodePolyline($ride['route']);

            // Check if both pickup and destination points are on the route
            if ($this->isPointOnRoute($pickup, $routePoints) && $this->isPointOnRoute($destination, $routePoints)) {
                // Fetch ride owner details
                $ownerDetails = $this->driverRepository->getDriverById($ride['driver_id']);
                $vehicleDetails = $this->vehicleRepository->getVehicleById($ride['vehicle_id']);
                if ($ownerDetails) {
                    $ride['owner_details'] = $ownerDetails;
                }
                if ($vehicleDetails) {
                    $ride['vehicle_details'] = $vehicleDetails;
                }

                $suggestedRides[] = $ride;
            }
        }

        return $suggestedRides;
    }

    private function decodePolyline($polyline)
    {
        $points = [];
        $index = 0;
        $len = strlen($polyline);
        $lat = 0;
        $lng = 0;

        while ($index < $len) {
            $b = 0;
            $shift = 0;
            $result = 0;
            do {
                $b = ord($polyline[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = 0;
            $result = 0;
            do {
                $b = ord($polyline[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $points[] = ['lat' => $lat / 1E5, 'lng' => $lng / 1E5];
        }

        return $points;
    }

    private function isPointOnRoute($point, $routePoints, $tolerance = 100) // tolerance in meters
    {
        foreach ($routePoints as $routePoint) {
            if ($this->haversineDistance($point, $routePoint) < $tolerance) {
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
