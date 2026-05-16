<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/businesses') ?>" class="text-sm text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-1"></i> Back to Businesses
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Business</h2>

        <form action="<?= base_url('admin/businesses/update/' . $business['business_id']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                    <input type="text" name="name" required value="<?= esc($business['name']) ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" required value="<?= esc($business['phone']) ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required value="<?= esc($business['email']) ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="active" <?= $business['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $business['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                    Update Business
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">API Credentials</h2>

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business ID</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="<?= $business['business_id'] ?>"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono text-gray-600">
                    <button onclick="copyToClipboard('<?= $business['business_id'] ?>')" class="px-3 py-2.5 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 text-gray-600">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Public Key</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="<?= $business['public_key'] ?>"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono text-gray-600">
                    <button onclick="copyToClipboard('<?= $business['public_key'] ?>')" class="px-3 py-2.5 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 text-gray-600">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="<?= $business['secret_key'] ?>"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono text-gray-600">
                    <button onclick="copyToClipboard('<?= $business['secret_key'] ?>')" class="px-3 py-2.5 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 text-gray-600">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="<?= $business['webhook_secret'] ?>"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono text-gray-600">
                    <button onclick="copyToClipboard('<?= $business['webhook_secret'] ?>')" class="px-3 py-2.5 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 text-gray-600">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard!');
    });
}
</script>
<?= $this->endSection() ?>
