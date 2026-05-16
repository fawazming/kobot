<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500">Manage your businesses</p>
    </div>
    <a href="<?= base_url('admin/businesses/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i>
        New Business
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <?php if (empty($businesses)): ?>
        <div class="p-12 text-center text-gray-400">
            <i class="fas fa-building text-5xl mb-4"></i>
            <p class="text-lg font-medium">No businesses yet</p>
            <p class="text-sm mt-1">Create your first business to get started</p>
            <a href="<?= base_url('admin/businesses/create') ?>" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Create Business</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Business ID</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($businesses as $b): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-blue-600"><?= $b['business_id'] ?></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800"><?= esc($b['name']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= esc($b['email']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= esc($b['phone']) ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $b['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                <?= esc($b['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= date('M d, Y', strtotime($b['created_at'])) ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/businesses/edit/' . $b['business_id']) ?>" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('admin/businesses/delete/' . $b['business_id']) ?>" class="text-red-600 hover:text-red-800" title="Delete" onclick="return confirm('Delete this business?')">
                                <i class="fas fa-trash"></i>
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
