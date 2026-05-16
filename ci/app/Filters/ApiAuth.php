<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\BusinessService;

class ApiAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $publicKey = $request->getHeaderLine('X-API-Key');
        $secretKey = $request->getHeaderLine('X-API-Secret');

        if (empty($publicKey) || empty($secretKey)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status' => false,
                    'message' => 'Missing API credentials',
                ]);
        }

        $businessService = new BusinessService();
        $business = $businessService->getByPublicKey($publicKey);

        if (!$business || $business['secret_key'] !== $secretKey) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status' => false,
                    'message' => 'Invalid API credentials',
                ]);
        }

        if ($business['status'] !== 'active') {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status' => false,
                    'message' => 'Business account is inactive',
                ]);
        }

        $request->setGlobal('business', $business);

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
