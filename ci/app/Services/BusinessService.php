<?php

namespace App\Services;

use App\Models\BusinessModel;
use App\Libraries\PaymentGateway;

class BusinessService
{
    protected BusinessModel $model;

    public function __construct()
    {
        $this->model = new BusinessModel();
    }

    public function getAll(int $perPage = 10)
    {
        return $this->model->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function getById(int $id): ?array
    {
        $result = $this->model->find($id);
        return $result ? $result : null;
    }

    public function getByBusinessId(string $businessId): ?array
    {
        $result = $this->model->where('business_id', $businessId)->first();
        return $result ? $result : null;
    }

    public function getByPublicKey(string $publicKey): ?array
    {
        $result = $this->model->where('public_key', $publicKey)->first();
        return $result ? $result : null;
    }

    public function create(array $data): array
    {
        $businessId = PaymentGateway::generateBusinessId();

        $existing = $this->model->where('business_id', $businessId)->first();
        while ($existing) {
            $businessId = PaymentGateway::generateBusinessId();
            $existing = $this->model->where('business_id', $businessId)->first();
        }

        $insertData = [
            'business_id'    => $businessId,
            'name'           => $data['name'],
            'phone'          => $data['phone'],
            'email'          => $data['email'],
            'public_key'     => PaymentGateway::generateApiKey(),
            'secret_key'     => PaymentGateway::generateSecretKey(),
            'webhook_secret' => PaymentGateway::generateWebhookSecret(),
            'status'         => $data['status'] ?? 'active',
        ];

        $this->model->insert($insertData);
        $insertData['id'] = $this->model->getInsertID();

        return $insertData;
    }

    public function update(int $id, array $data): bool
    {
        $updateData = [];
        if (isset($data['name'])) $updateData['name'] = $data['name'];
        if (isset($data['phone'])) $updateData['phone'] = $data['phone'];
        if (isset($data['email'])) $updateData['email'] = $data['email'];
        if (isset($data['status'])) $updateData['status'] = $data['status'];

        if (empty($updateData)) return false;

        return $this->model->update($id, $updateData);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function getPager()
    {
        return $this->model->pager;
    }

    public function countActive(): int
    {
        return $this->model->where('status', 'active')->countAllResults();
    }

    public function countAll(): int
    {
        return $this->model->countAll();
    }
}