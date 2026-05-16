<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500">View and manage all transactions</p>
    </div>
    <form method="GET" action="<?= base_url('admin/transactions') ?>" class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">All Status</option>
                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="success" <?= ($filters['status'] ?? '') === 'success' ? 'selected' : '' ?>>Success</option>
                <option value="failed" <?= ($filters['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                <option value="expired" <?= ($filters['status'] ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
            </select>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" placeholder="Search..." value="<?= $filters['search'] ?? '' ?>"
                    class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none w-48">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition-colors">Filter</button>
            <?php if (!empty($filters)): ?>
                <a href="<?= base_url('admin/transactions') ?>" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <?php if (empty($transactions)): ?>
        <div class="p-12 text-center text-gray-400">
            <i class="fas fa-credit-card text-5xl mb-4"></i>
            <p class="text-lg font-medium">No transactions found</p>
            <p class="text-sm mt-1">Transactions will appear here once created</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Business</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Original</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Payable</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Verified</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($transactions as $txn): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-blue-600"><?= $txn['transaction_id'] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= $txn['business_id'] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= $txn['email'] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= format_amount($txn['original_amount']) ?></td>
                        <td class="px-6 py-4 text-sm font-medium"><?= format_amount($txn['payable_amount']) ?></td>
                        <td class="px-6 py-4"><?= status_badge($txn['payment_status']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($txn['webhook_verified']): ?>
                                <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                            <?php else: ?>
                                <span class="text-gray-400"><i class="fas fa-times-circle"></i></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= format_date($txn['created_at'], 'M d, Y') ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/transactions/view/' . $txn['transaction_id']) ?>" class="text-blue-600 hover:text-blue-800" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
