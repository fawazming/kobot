<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\TransactionService;
use App\Services\BusinessService;

class Dashboard extends BaseController
{
    protected TransactionService $transactionService;
    protected BusinessService $businessService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
        $this->businessService = new BusinessService();
        helper(['kobo']);
    }

    public function index()
    {
        $stats = $this->getStats();
        $recentTransactions = $this->transactionService->getAll(10);
        $pager = $this->transactionService->getPager();

        return view('admin/dashboard/index', [
            'stats'              => $stats,
            'recentTransactions' => $recentTransactions,
            'pager'              => $pager,
            'title'              => 'Dashboard',
        ]);
    }

    public function stats()
    {
        $stats = $this->getStats();

        return $this->response
            ->setContentType('application/json')
            ->setJSON(['status' => true, 'data' => $stats]);
    }

    private function getStats(): array
    {
        return [
            'total_transactions'  => $this->transactionService->countTotal(),
            'successful_payments' => $this->transactionService->countByStatus('success'),
            'pending_payments'    => $this->transactionService->countByStatus('pending'),
            'failed_payments'     => $this->transactionService->countByStatus('failed'),
            'total_businesses'    => $this->businessService->countAll(),
            'active_businesses'   => $this->businessService->countActive(),
            'total_revenue'       => $this->transactionService->sumAllPayable(),
        ];
    }
}
