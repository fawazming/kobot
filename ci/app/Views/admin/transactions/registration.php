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

    <div class="overflow-hidden border border-gray-100 rounded-lg">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                <tr>
                    <th class="py-3 px-6">Field</th>
                    <th class="py-3 px-6">Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($jsonData as $key => $value): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-gray-700 capitalize"><?= str_replace(['_', '-'], ' ', $key) ?></td>
                    <td class="py-4 px-6 text-gray-600">
                        <?php if (is_array($value)): ?>
                            <pre class="text-xs font-mono bg-gray-50 p-2 rounded"><?= json_encode($value, JSON_PRETTY_PRINT) ?></pre>
                        <?php else: ?>
                            <?= esc($value) ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
