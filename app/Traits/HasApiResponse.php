<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait HasApiResponse
{
    protected static $response = [
        'meta' => [
            'code'      => 200,
            'status'    => true,
            'message'   => null,
        ],
        'result' => null
    ];

    public static function successReponse($data = null, $message = null, $code = 200)
    {
        self::$response['meta']['code']      = $code;
        self::$response['meta']['message']   = $message;
        self::$response['result']            = $data;

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public static function errorResponse($message = null, $code = 500)
    {
        self::$response['meta']['code']      = $code;
        self::$response['meta']['status']    = false;
        self::$response['meta']['message']   = $message;

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public function validationResponse($errors = null, $message = 'Validation Errors', $code = 422)
    {
        self::$response['meta']['code']     = $code;
        self::$response['meta']['status']   = false;
        self::$response['meta']['message']  = $message;
        self::$response['result']           = $errors;

        throw new HttpResponseException(response()->json(self::$response, self::$response['meta']['code']));
    }
}
