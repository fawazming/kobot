<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Services\TransactionService;

class Transaction extends BaseController
{
    use ResponseTrait;

    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public function create()
    {
        $rules = [
            'email'  => 'required|valid_email',
            'amount' => 'required|numeric|greater_than[10]',
        ];

        if ($this->request->getJSON()) {
            $input = $this->request->getJSON(true);
        } else {
            $input = $this->request->getPost();
        }

        if (!$this->validateData($input, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors(),
                ]);
        }

        $business = $this->request->fetchGlobal('business');

        $registrationData = [];
        if (!empty($input['registration'])) {
            $registrationData = is_array($input['registration'])
                ? $input['registration']
                : json_decode($input['registration'], true) ?? [];
        }

        $data = [
            'business' => $business,
            'email'    => $input['email'],
            'amount'   => $input['amount'],
        ];
        try {
            $result = $this->transactionService->create($data, $registrationData);

            
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // return $this->respond($data);
        
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->setStatusCode(200)
            ->setJSON($result);
            // return $this->response
            //     ->setStatusCode(201)
            //     ->setJSON($result);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            log_message('error', '[Transaction Create] ' . $e->getMessage());

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => 'An error occurred while creating transaction',
                ]);
        }
    }

    public function status($transactionId)
    {
        if (empty($transactionId)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'Transaction ID is required',
                ]);
        }

        $result = $this->transactionService->getStatus($transactionId);

        if (!$result) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Transaction not found',
                ]);
        }

        
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // return $this->respond($data);
        
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->setStatusCode(200)
            ->setJSON($result);

        // return $this->response
        //     ->setStatusCode(200)
        //     ->setJSON($result);
    }

    public function registration($transactionId)
    {
        if (empty($transactionId)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'Transaction ID is required',
                ]);
        }

        $transaction = $this->transactionService->getStatus($transactionId);

        if (!$transaction) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Transaction not found',
                ]);
        }

        $transactionStatus = is_array($transaction)
            ? ($transaction['status'] ?? null)
            : ($transaction->status ?? null);

        if (!in_array(strtolower((string) $transactionStatus), ['success', 'paid', 'paid_for'], true)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => 'Transaction is not paid',
                    'current_status' => $transactionStatus,
                ]);
        }

        if ($this->request->getJSON()) {
            $input = $this->request->getJSON(true);
        } else {
            $input = $this->request->getPost();
        }

        $registrationData = [];
        if (!empty($input['registration'])) {
            $registrationData = is_array($input['registration'])
                ? $input['registration']
                : json_decode($input['registration'], true) ?? [];
        } else {
            $registrationData = $input;
        }

        if (empty($registrationData)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => 'Registration data is required',
                ]);
        }

        try {
            $result = $this->transactionService->registration($transactionId, $registrationData);

            if (!$result) {
                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'status' => false,
                        'message' => 'Transaction not found or registration failed',
                    ]);
            }

            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->setStatusCode(200)
                ->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', '[Transaction Registration] ' . $e->getMessage());

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => 'An error occurred while registering transaction',
                ]);
        }
    }
}
