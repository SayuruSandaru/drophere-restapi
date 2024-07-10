<?php

namespace App\Controller;

use App\Service\UserService;
use App\Utility\ResponseUtility;

class UserController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    
    public function getDisputeById($request)
    {
        $dispute_id = $request['dispute_id'];
        $res = $this->userService->getDisputeById($dispute_id);
        
        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['dispute' => $res['dispute']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                200
            );
        }
    }
    
    public function createDispute($request)
    {
        $dispute_id = $request['dispute_id'];
        $status = $request['status'];
        $category = $request['category'];
        $email = $request['email'];
        $phone_no = $request['phone_no'];
        $message = $request['message'];
    
        $res = $this->userService->createDispute($dispute_id, $status, $category, $email, $phone_no, $message);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                200
            );
        }
    }
    
    public function getAllDisputes()
    {
        $res = $this->userService->getAllDisputes();
    
        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['disputes' => $res['disputes']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                500
            );
        }
    }
    
    public function updateStatus($request)
    {
        $dispute_id = $request['dispute_id'];
        $status = $request['status'];

        $res = $this->userService->updateStatus($dispute_id, $status);
    
        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['dispute' => $res['dispute']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                500
            );
        }
    }
}
?>
