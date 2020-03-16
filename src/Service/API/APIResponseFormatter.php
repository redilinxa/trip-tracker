<?php

/**
 * @Author: Redi Linxa
 * @Date:   2018-07-04 08:57:50
 * Defines a consistent response to be used by BaseController methods.
 */

namespace App\Service\API;

class APIResponseFormatter
{
    /**
     * @param $code
     * @param $data
     * @param $message
     * @param $internal_code
     * @return array
     */
    public function format($code, $data, $message, $internal_code)
    {
        return [
            "code" => $code,
            "message" => $message,
            "internal_code" => $internal_code,
            "data" => $data
        ];
    }
}
