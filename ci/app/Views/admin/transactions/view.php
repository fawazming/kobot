<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/transactions') ?>" class="text-sm text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-1"></i> Back to Transactions
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Transaction Details</h2>

        <dl class="space-y-4">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Transaction ID</dt>
                <dd class="text-sm font-mono font-medium text-gray-800"><?= $transaction['transaction_id'] ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Business</dt>
                <dd class="text-sm font-medium text-gray-800"><?= $transaction['business_id'] ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Email</dt>
                <dd class="text-sm text-gray-800"><?= $transaction['email'] ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Original Amount</dt>
                <dd class="text-sm font-medium text-gray-800"><?= format_amount($transaction['original_amount']) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Payable Amount</dt>
                <dd class="text-sm font-medium text-gray-800"><?= format_amount($transaction['payable_amount']) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Kobo Difference</dt>
                <dd class="text-sm font-medium text-gray-800">
                    <?php
                    $diff = $transaction['original_amount'] - $transaction['payable_amount'];
                    echo format_amount($diff < 0 ? -$diff : $diff);
                    ?>
                </dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Status</dt>
                <dd><?= status_badge($transaction['payment_status']) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Webhook Verified</dt>
                <dd>
                    <?php if ($transaction['webhook_verified']): ?>
                        <span class="text-green-600"><i class="fas fa-check-circle"></i> Verified</span>
                    <?php else: ?>
                        <span class="text-yellow-600"><i class="fas fa-clock"></i> Not Verified</span>
                    <?php endif; ?>
                </dd>
            </div>
            <?php if ($transaction['registration_id']): ?>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <dt class="text-sm text-gray-500">Registration</dt>
                <dd>
                    <a href="<?= base_url('admin/transactions/registration/' . $transaction['registration_id']) ?>" class="text-blue-600 hover:underline text-sm">
                        <?= $transaction['registration_id'] ?> <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </dd>
            </div>
            <?php endif; ?>
            <div class="flex justify-between py-2">
                <dt class="text-sm text-gray-500">Created At</dt>
                <dd class="text-sm text-gray-800"><?= format_date($transaction['created_at']) ?></dd>
            </div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Actions</h2>

        <div class="space-y-4">
            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Transaction ID for Polling</h3>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="<?= $transaction['transaction_id'] ?>"
                        class="w-full px-3 py-2 bg-white border border-blue-300 rounded-lg text-sm font-mono">
                    <button onclick="navigator.clipboard.writeText('<?= $transaction['transaction_id'] ?>')"
                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 rounded-lg text-blue-700">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="text-xs text-blue-600 mt-2">
                    Polling endpoint: GET /api/v1/transaction/status/<?= $transaction['transaction_id'] ?>
                </p>
            </div>

            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h3 class="text-sm font-medium text-yellow-800 mb-1">Refresh Status</h3>
                <p class="text-xs text-yellow-600 mb-3">Manually check and refresh transaction status</p>
                <a href="<?= base_url('admin/transactions/refresh/' . $transaction['transaction_id']) ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 hover:bg-yellow-200 rounded-lg text-sm font-medium text-yellow-800 transition-colors">
                    <i class="fas fa-sync"></i> Refresh Now
                </a>
            </div>

            <?php if ($transaction['payment_status'] === 'success'): ?>
            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                <h3 class="text-sm font-medium text-green-800 mb-1">Payment Confirmed</h3>
                <p class="text-xs text-green-600">
                    This payment has been verified and confirmed via webhook.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
