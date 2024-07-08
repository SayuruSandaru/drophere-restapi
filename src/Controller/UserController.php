<?php
namespace App\Controller;

use App\Service\UserService;
use App\Utility\ResponseUtility;

class UserController {
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    
    
   
    public function getAllUsers()
    {
        $res = $this->userService->getAllUsers();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['users' => $res['users']],
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


    public function getUserById($id)
    {
        $res = $this->userService->getUserById($id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['user' => $res['user']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                404
            );
        }
    }


    public function getUserDetails($id)
    {
        $res = $this->userService->getUserDetails($id);

        if ($res['status']) {
            $responseData = [
                'user' => $res['user'],
                'isDriver' => $res['isDriver']
            ];

            if ($res['isDriver']) {
                $responseData['driverDetails'] = $res['driverDetails'];
            }

            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                $responseData,
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                $res['message'] === 'User not found' ? 404 : 500
            );
        }
    }


   
}
?>