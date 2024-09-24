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

    public function createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location, $vehicle_id, $passngerCount)
    {
        try {
            $res =  $this->rideRepository->createRide($driver_id, $status, $start_time, $current_location, $route_id, $start_location, $end_location, $vehicle_id, $passngerCount);
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
            if (empty($ride)) {
                return [
                    'status' => false,
                    'message' => 'Ride not found'
                ];
            }
            $ownerDetails = $this->driverRepository->getDriverById($ride['driver_id']);
            $vehicleDetails = $this->vehicleRepository->getVehicleById($ride['vehicle_id']);
            if ($ownerDetails) {
                $ride['owner_details'] = $ownerDetails;
            }

            if ($vehicleDetails) {
                $ride['vehicle_details'] = $vehicleDetails;
            }

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

    public function getRideByStatus($status)
    {
        try {
            $rides = $this->rideRepository->getRideByStatus($status);
            if (count($rides) == 0) {
                return [
                    'status' => false,
                    'message' => 'Ride not found'
                ];
            }

            foreach ($rides as $key => $ride) {
                if (!is_array($ride)) {
                    continue;
                }
                $ownerDetails = $this->driverRepository->getDriverById($ride['driver_id']);
                if ($ownerDetails) {
                    $rides[$key]['owner_details'] = $ownerDetails;
                } else {
                    $rides[$key]['owner_details'] = 'Driver not found';
                }
                $vehicleDetails = $this->vehicleRepository->getVehicleById($ride['vehicle_id']);
                if ($vehicleDetails) {
                    $rides[$key]['vehicle_details'] = $vehicleDetails;
                } else {
                    $rides[$key]['vehicle_details'] = 'Vehicle not found';
                }
            }

            return [
                'status' => true,
                'rides' => $rides
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to retrieve rides: ' . $e->getMessage()
            ];
        }
    }

    public function updateRideStatus($ride_id, $status)
    {
        try {
            $res = $this->rideRepository->updateRideByStatus($ride_id, $status);
            if ($res) {
                return [
                    'status' => true,
                    'message' => 'Ride status updated successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Failed to update ride status'
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to update ride status: ' . $e->getMessage()
            ];
        }
    }

    public function deleteRide($ride_id)
    {
        try {
            $res = $this->rideRepository->deleteRide($ride_id);
            if ($res) {
                return [
                    'status' => true,
                    'message' => 'Ride deleted successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Failed to delete ride'
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to delete ride: ' . $e->getMessage()
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

    public function getAllRidesByDriverId($driver_id)
    {
        try {
            $rides = $this->rideRepository->getAllRidesByDriver($driver_id);
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

    public function searchRides($pickup, $destination, $date, $passngerCountr)
    {

        $rides = $this->rideRepository->getAllRides();
        $suggestedRides = [];

        foreach ($rides as $ride) {
            $routePoints = $this->decodePolyline($ride['route']);

            if ($this->isPointOnRoute($pickup, $routePoints) && $this->isPointOnRoute($destination, $routePoints)) {
                $ownerDetails = $this->driverRepository->getDriverById($ride['driver_id']);
                $vehicleDetails = $this->vehicleRepository->getVehicleById($ride['vehicle_id']);
                $fee = $this->rideRepository->calculateFee($pickup, $destination, $vehicleDetails['type']);
                if ($ownerDetails) {
                    $ride['owner_details'] = $ownerDetails;
                }
                if ($vehicleDetails) {
                    $ride['vehicle_details'] = $vehicleDetails;
                }
                if ($fee) {
                    $ride['fee'] = $fee['fee'];
                $ride['individualFee'] = ceil($fee['fee'] / $passngerCountr);
                $ride['distance'] = $fee['distance'];
                }
                $suggestedRides[] = $ride;
                
            }
        }
        return $suggestedRides;
    }

    public function searchRidesByName($pickup, $destination, $date, $passengerCount, $pickupCoordinates, $destinationCoordinates)
    {
        $rides = $this->rideRepository->searchByName($pickup, $destination, $date, $passengerCount);
        $suggestedRides = [];
        foreach ($rides as $ride) {
            $ownerDetails = $this->driverRepository->getDriverById($ride['driver_id']);
            $vehicleDetails = $this->vehicleRepository->getVehicleById($ride['vehicle_id']);
            $fee = $this->rideRepository->calculateFee($pickupCoordinates, $destinationCoordinates, $vehicleDetails['type']);
            if ($ownerDetails) {
                $ride['owner_details'] = $ownerDetails;
            }
            if ($vehicleDetails) {
                $ride['vehicle_details'] = $vehicleDetails;
            }
            if ($fee) {
                $ride['fee'] = $fee['fee'];
                $ride['individualFee'] = ceil($fee['fee'] / $passengerCount);
                $ride['distance'] = $fee['distance'];
            }
            $suggestedRides[] = $ride;
        }
        return $suggestedRides;
    }



    public function getDirections($pickup, $destination)
    {
        try {
            $res = $this->rideRepository->getDirections($pickup, $destination);
            if (empty($res)) {
                return [
                    'status' => false,
                    'message' => 'No directions found'
                ];
            } else {

                return [
                    'status' => true,
                    'directions' => $res
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
