<?php

namespace App\Service;

use App\Repository\UserRepository;


class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }


    public function createDispute($status, $category, $message, $userId)
    {
        try {
            $res =  $this->userRepository->createDispute($userId, $category, $status, $message);
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
            $dispute = $this->userRepository->getAllDisputes();
            return [
                'status' => true,
                'disputes' => $dispute
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
            $dispute = $this->userRepository->getdisputeById($dispute_id);
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
