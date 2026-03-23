<?php

namespace App\Traits;

trait ApiResponse {

    protected function responseJson($status = null, $message = "", $errorCode = null, $data = null, $httpCode = null) {
        return response()->json([
            'status'    => $status,
            'errorCode' => $errorCode,
            'message'   => $message,
            'result'      => $data
        ], $httpCode);
    }
}
