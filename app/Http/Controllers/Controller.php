<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Returns a json response
     *
     * @param bool $success Success status of what was being done
     * @param string $message Status message related to the response
     * @param mixed $data Any data to be sent back with the response
     * @param array $errors Any errors to be sent back
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function json($success, $message, $data = null, $errors = []){
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        // If data has to be sent
        if($data){
            $response['data'] = $data;
        }

        // If any errrors
        if(is_array($errors) && count($errors) > 0){
            $response['errors'] = $errors;
        }

        return response()->json($response);
    }
}
