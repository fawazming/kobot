<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\RegistrationModel;
use App\Libraries\PaymentGateway;

class TransactionService
{
    protected TransactionModel $model;
    protected RegistrationModel $registrationModel;

    public function __construct()
    {
        $this->model = new TransactionModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function create(array $data, array $registrationData = []): array
    {
        $business = $data['business'];
        $email = $data['email'];
        $amount = (float) $data['amount'];
        $model = new TransactionModel();
        $registrationModel = new RegistrationModel();

        $paymentCalc = PaymentGateway::calculatePayableAmount($amount);

        $transactionId = $this->generateUniqueTransactionId();
        $registrationId = PaymentGateway::generateRegistrationId();

        $transactionData = [
            'transaction_id'  => $transactionId,
            'business_id'     => $business['business_id'],
            'email'           => $email,
            'original_amount' => $paymentCalc['original_amount'],
            'payable_amount'  => $paymentCalc['payable_amount'],
            'payment_status'  => 'pending',
            'registration_id' => $registrationId,
            'webhook_verified'=> 0,
        ];

        if (!empty($registrationData)) {
            $transactionData['metadata'] = json_encode($registrationData);
        }

        $model->insert($transactionData);

        if (!empty($registrationData)) {
            $registrationModel->insert([
                'registration_id' => $registrationId,
                'transaction_id'  => $transactionId,
                'json_data'       => json_encode($registrationData),
            ]);
        }

        return [
            'status'          => true,
            'transaction_id'  => $transactionId,
            'original_amount' => $paymentCalc['original_amount'],
            'payable_amount'  => $paymentCalc['payable_amount'],
            'currency'        => 'NGN',
            'message'         => 'Transaction created successfully',
            'business_id'     => $business['business_id'],
            'registration_id' => $registrationId,
        ];
    }

    public function getStatus(string $transactionId): ?array
    {
        $result = $this->model->where('transaction_id', $transactionId)->first();
        if (!$result) return null;

        $txn = $result;

        return [
            'status'          => true,
            'transaction_id'  => $txn['transaction_id'],
            'payment_status'  => $txn['payment_status'],
            'amount_paid'     => (float) $txn['payable_amount'],
            'business_id'     => $txn['business_id'],
            'registration_id' => $txn['registration_id'],
        ];
    }

    public function getByTransactionId(string $transactionId): ?array
    {
        $result = $this->model->where('transaction_id', $transactionId)->first();
        return $result ? $result : null;
    }

    public function getByAmount(float $amount): array
    {
        $results = $this->model->where('payable_amount', $amount)
                               ->where('payment_status', 'pending')
                               ->first();
        return $results;
    }

    public function getAll(int $perPage = 10, array $filters = [])
    {
        $query = $this->model->orderBy('id', 'DESC');

        if (!empty($filters['status'])) {
            $query->where('payment_status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->groupStart()
                ->like('transaction_id', $search)
                ->orLike('email', $search)
                ->orLike('business_id', $search)
                ->groupEnd();
        }

        return $query->paginate($perPage);
    }

    public function getPager()
    {
        return $this->model->pager;
    }

    public function markAsSuccess(string $transactionId): bool
    {
        return $this->model->where('transaction_id', $transactionId)
            ->set(['payment_status' => 'success', 'webhook_verified' => 1])
            ->update();
    }

    public function countTotal(): int
    {
        return $this->model->countAll();
    }

    public function countByStatus(string $status): int
    {
        return $this->model->where('payment_status', $status)->countAllResults();
    }

    public function sumPayableByStatus(string $status): float
    {
        $result = $this->model->selectSum('payable_amount', 'total')
            ->where('payment_status', $status)
            ->get()
            ->getRow();
        return $result ? (float) $result->total : 0;
    }

    public function sumAllPayable(): float
    {
        $result = $this->model->selectSum('payable_amount', 'total')
            ->where('payment_status', 'success')
            ->get()
            ->getRow();
        return $result ? (float) $result->total : 0;
    }

    private function generateUniqueTransactionId(): string
    {
        $id = PaymentGateway::generateTransactionId();
        $existing = $this->model->where('transaction_id', $id)->first();
        while ($existing) {
            $id = PaymentGateway::generateTransactionId();
            $existing = $this->model->where('transaction_id', $id)->first();
        }
        return $id;
    }
}