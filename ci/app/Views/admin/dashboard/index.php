<?= $this->extend('admin/layouts/main') ?>
<?php
if (!function_exists('status_badge')) {
    function status_badge($status) {
        $classes = [
            'success' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed'  => 'bg-red-100 text-red-800',
        ];
        $class = $classes[strtolower($status)] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $class . '">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'M d, Y') {
        if (!$date) return 'N/A';
        return date($format, strtotime($date));
    }
}
?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Transactions</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_transactions']) ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-credit-card text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Successful</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?= number_format($stats['successful_payments']) ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1"><?= number_format($stats['pending_payments']) ?></p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Revenue</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_revenue'], 2) ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-naira-sign text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Businesses</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_businesses']) ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-indigo-600 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2"><?= $stats['active_businesses'] ?> active</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Failed</p>
                <p class="text-2xl font-bold text-red-600 mt-1"><?= number_format($stats['failed_payments']) ?></p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Businesses</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?= number_format($stats['active_businesses']) ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Recent Transactions</h2>
        <a href="<?= base_url('admin/transactions') ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            View All <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <?php if (empty($recentTransactions)): ?>
        <div class="p-6 text-center text-gray-400">
            <i class="fas fa-inbox text-4xl mb-3"></i>
            <p>No transactions yet</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Business</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($recentTransactions as $txn): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-blue-600">
                            <a href="<?= base_url('admin/transactions/view/' . $txn['transaction_id']) ?>" class="hover:underline">
                                <?= $txn['transaction_id'] ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= esc($txn['business_name'] ?? $txn['business_id']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= $txn['email'] ?></td>
                        <td class="px-6 py-4 text-sm font-medium"><?= number_format($txn['payable_amount'], 2) ?></td>
                        <td class="px-6 py-4"><?= status_badge($txn['payment_status']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= format_date($txn['created_at'], 'M d, Y') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
