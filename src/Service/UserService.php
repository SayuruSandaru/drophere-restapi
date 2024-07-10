<?php

namespace App\Service;

use App\Repository\UserRepository;

class RideService
{
    private $disputeRepository;

    public function __construct()
    {
        $this->disputeRepository = new DisputeRepository();
    }


    public function createDispute($dispute_id, $status, $category, $email, $phone_no, $message)
    {
        try {
            $res =  $this->disputeRepository->createDispute($dispute_id, $status, $category, $email, $phone_no, $message);
            if ($res) {
                return [
                    'status' => true,
                    'message' => 'Dispute created successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Error in creating Dispute'
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

    public function getAllDisputes()
    {
        try {
            $dispute = $this->disputeRepository->getAllDisputes();
            return [
                'status' => true,
                'disputes' => $disputes
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getdisputeById($dispute_id)
    {
        try {
            $dispute = $this->disputeRepository->getdisputeById($dispute_id);
            return [
                'status' => true,
                'dispute' => $dispute
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateStatus($dispute_id, $status)
{
    try {
        $result = $this->userRepository->updateStatus($dispute_id, $status);
        return [
            'status' => true,
            'message' => 'Dispute status updated successfully'
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