<?php

namespace App\Service;

use App\Repository\DeliveryRepository;

class DeliveryService
{
    private $deliveryRepository;

    public function __construct()
    {
        $this->deliveryRepository = new DeliveryRepository();
    }

    public function createDelivery(array $data)
    {
        try {
            $res = $this->deliveryRepository->createDelivery($data);
            if ($res !== 0) {
                return [
                    'status' => true,
                    'message' => 'Delivery created successfully',
                    'delivery_id' => $res
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Error in creating delivery'
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

    public function getDeliveryById($deliveryId)
    {
        try {
            $delivery = $this->deliveryRepository->getDeliveryById($deliveryId);
            return [
                'status' => true,
                'delivery' => $delivery
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAllDeliveries()
    {
        try {
            $deliveries = $this->deliveryRepository->getAllDeliveries();
            return [
                'status' => true,
