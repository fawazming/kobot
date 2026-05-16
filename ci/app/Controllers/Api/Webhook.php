<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\WebhookService;

class Webhook extends BaseController
{
    protected WebhookService $webhookService;

    public function __construct()
    {
        $this->webhookService = new WebhookService();
    }

    public function payment()
    {
        $payload = $this->request->getJSON(true);
        $signature = $this->request->getHeaderLine('X-Payment-Signature');

        if (empty($payload)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'Invalid or empty webhook payload',
                ]);
        }

        $result = $this->webhookService->processWebhook($payload, $signature);

        if (!$result['status']) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON($result);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON($result);
    }
}
