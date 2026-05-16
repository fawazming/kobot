<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/businesses') ?>" class="text-sm text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-1"></i> Back to Businesses
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Create New Business</h2>

    <form action="<?= base_url('admin/businesses/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                <input type="text" name="name" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Enter business name">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Enter phone number">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Enter email address">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                Create Business
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
