<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-lg font-semibold text-gray-800 hidden sm:block"><?= $title ?? 'Dashboard' ?></h1>
    </div>

    <div class="flex items-center gap-4">
        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
            <i class="far fa-clock"></i>
            <span id="clock"></span>
        </div>

        <?php if (session()->has('admin_data')): ?>
            <div class="flex items-center gap-2 text-sm">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    <?= strtoupper(substr(session('admin_data')['username'] ?? 'A', 0, 1)) ?>
                </div>
                <span class="hidden sm:block text-gray-700 font-medium"><?= session('admin_data')['username'] ?? 'Admin' ?></span>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
    (function() {
        function updateClock() {
            const now = new Date();
            const opts = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const el = document.getElementById('clock');
            if (el) el.textContent = now.toLocaleTimeString('en-US', opts);
        }
        updateClock();
        setInterval(updateClock, 1000);
    })();
</script>
