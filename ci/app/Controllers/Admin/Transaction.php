<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\TransactionService;

class Transaction extends BaseController
{
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
        helper(['kobo']);
    }

    public function index()
    {
        $filters = [];
        if ($this->request->getGet('status')) {
            $filters['status'] = $this->request->getGet('status');
        }
        if ($this->request->getGet('search')) {
            $filters['search'] = $this->request->getGet('search');
        }

        $transactions = $this->transactionService->getAll(20, $filters);
        $pager = $this->transactionService->getPager();

        return view('admin/transactions/index', [
            'transactions' => $transactions,
            'pager'        => $pager,
            'title'        => 'Transactions',
            'filters'      => $filters,
        ]);
    }

    public function view($transactionId)
    {
        $transaction = $this->transactionService->getByTransactionId($transactionId);

        if (!$transaction) {
            return redirect()->to(base_url('admin/transactions'))
                ->with('error', 'Transaction not found');
        }

        return view('admin/transactions/view', [
            'transaction' => $transaction,
            'title'       => 'Transaction Details',
        ]);
    }

    public function refresh($transactionId)
    {
        $transaction = $this->transactionService->getByTransactionId($transactionId);

        if (!$transaction) {
            return redirect()->to(base_url('admin/transactions'))
                ->with('error', 'Transaction not found');
        }

        return redirect()->to(base_url('admin/transactions/view/' . $transactionId))
            ->with('success', 'Transaction status refreshed');
    }

    public function registration($registrationId)
    {
        $model = new \App\Models\RegistrationModel();
        $registration = $model->where('registration_id', $registrationId)->first();

        if (!$registration) {
            return redirect()->to(base_url('admin/transactions'))
                ->with('error', 'Registration not found');
        }

        $data = $registration;
        $jsonData = json_decode($data['json_data'], true);

        return view('admin/transactions/registration', [
            'registration' => $data,
            'jsonData'     => $jsonData,
            'title'        => 'Registration Data',
        ]);
    }
}
