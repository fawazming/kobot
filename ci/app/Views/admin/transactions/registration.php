<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="javascript:history.back()" class="text-sm text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Registration Data</h2>
            <p class="text-sm text-gray-500 mt-1">ID: <?= $registration['registration_id'] ?></p>
        </div>
        <span class="text-xs text-gray-400">Transaction: <?= $registration['transaction_id'] ?></span>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 overflow-x-auto">
        <pre class="text-sm font-mono text-gray-700 whitespace-pre-wrap"><?= json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
    </div>
</div>
<?= $this->endSection() ?>
