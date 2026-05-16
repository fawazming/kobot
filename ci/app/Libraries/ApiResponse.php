<?php

namespace App\Libraries;

class ApiResponse
{
    public static function success($data = [], string $message = 'Success', int $code = 200)
    {
        return service('response')
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON([
                'status' => true,
                'message' => $message,
                'data' => $data,
            ]);
    }

    public static function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return service('response')
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON($response);
    }

    public static function raw(array $data, int $code = 200)
    {
        return service('response')
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON($data);
    }
}
