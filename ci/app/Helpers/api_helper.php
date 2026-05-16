<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;

if (!function_exists('api_response')) {
    function api_response($data, string $message = 'Success', bool $status = true, int $code = 200): ResponseInterface
    {
        return service('response')
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON([
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ]);
    }
}

if (!function_exists('api_error')) {
    function api_error(string $message = 'Error', int $code = 400, $errors = null): ResponseInterface
    {
        $response = ['status' => false, 'message' => $message];
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        return service('response')
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON($response);
    }
}
