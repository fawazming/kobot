<?php if (session()->has('success')): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <i class="fas fa-check-circle"></i>
        <span class="text-sm font-medium"><?= session('success') ?></span>
        <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <i class="fas fa-exclamation-circle"></i>
        <span class="text-sm font-medium"><?= session('error') ?></span>
        <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
    </div>
<?php endif; ?>

<?php if (session()->has('errors')): ?>
    <?php foreach (session('errors') as $error): ?>
        <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2" x-data="{ show: true }" x-show="show">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm font-medium"><?= $error ?></span>
            <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
