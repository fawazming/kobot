<?php

namespace App\Services;

use App\Models\WebhookLogModel;
use App\Libraries\PaymentGateway;

class WebhookService
{
    protected WebhookLogModel $model;
    protected TransactionService $transactionService;
    protected BusinessService $businessService;

    public function __construct()
    {
        $this->model = new WebhookLogModel();
        $this->transactionService = new TransactionService();
        $this->businessService = new BusinessService();
    }

    public function processWebhook(array $payload, ?string $signature): array
    {
        $payloadJson = json_encode($payload);
        $payload['transaction_id'] = $payload['transaction_id'] ?? 'TXN-' . strtoupper(bin2hex(random_bytes(6)));

        $this->model->insert([
            'transaction_id' => $payload['transaction_id'],
            'payload'        => $payloadJson,
            'signature'      => $signature ?? '',
            'status'         => 'received',
        ]);
        $logId = $this->model->getInsertID();

        if (empty($payload['transaction_id'])) {
            $this->updateLogStatus($logId, 'failed');
            return ['status' => false, 'message' => 'Missing transaction_id in payload'];
        }

        $transaction = $this->transactionService->getByAmount($payload['amount']);
        if (!$transaction) {
            $this->updateLogStatus($logId, 'failed');
            return ['status' => false, 'message' => 'Transaction not found'];
        }

        if ($transaction['payment_status'] === 'success') {
            $this->updateLogStatus($logId, 'duplicate');
            return ['status' => true, 'message' => 'Transaction already processed'];
        }

        if (empty($signature)) {
            $this->updateLogStatus($logId, 'failed');
            return ['status' => false, 'message' => 'Missing webhook signature'];
        }

        $business = $this->businessService->getByBusinessId($transaction['business_id']);
        if (!$business) {
            $this->updateLogStatus($logId, 'failed');
            return ['status' => false, 'message' => 'Business not found'];
        }

        $isValid = PaymentGateway::verifyWebhookSignature(
            $payloadJson,
            $signature,
            $business['webhook_secret']
        );

        if (!$isValid) {
            $this->updateLogStatus($logId, 'failed');
            return ['status' => false, 'message' => 'Invalid webhook signature'];
        }

        $expectedAmount = (float) $transaction['payable_amount'];
        $receivedAmount = (float) ($payload['amount_paid'] ?? 0);

        if (abs($expectedAmount - $receivedAmount) > 0.01) {
            $this->updateLogStatus($logId, 'failed');
            return [
                'status'  => false,
                'message' => 'Amount mismatch. Expected: ' . $expectedAmount . ', Received: ' . $receivedAmount,
            ];
        }

        $this->transactionService->markAsSuccess($transaction['transaction_id']);
        $this->updateLogStatus($logId, 'verified');

        return [
            'status'  => true,
            'message' => 'Webhook processed successfully',
            'data'    => [
                'transaction_id' => $transaction['transaction_id'],
                'payment_status' => 'success',
            ],
        ];
    }

    private function updateLogStatus(int $logId, string $status): void
    {
        $this->model->update($logId, ['status' => $status]);
    }

    public function getAll(int $perPage = 10)
    {
        return $this->model->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function getPager()
    {
        return $this->model->pager;
    }

    public function countAll(): int
    {
        return $this->model->countAll();
    }
}