<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 shadow-sm transform transition-transform duration-300 lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:w-20'">
    <div class="flex items-center gap-3 px-6 h-16 border-b border-gray-200">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
            <span class="text-white font-bold text-sm">KT</span>
        </div>
        <span class="font-bold text-lg text-gray-800 lg:block" :class="sidebarOpen ? 'block' : 'lg:hidden'">KoboTrack</span>
    </div>

    <nav class="p-4 space-y-1">
        <a href="<?= base_url('admin') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors <?= uri_string() === 'admin' ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
            <i class="fas fa-chart-pie w-5 text-center <?= uri_string() === 'admin' ? 'text-white' : 'text-gray-400' ?>"></i>
            <span :class="sidebarOpen ? 'block' : 'lg:hidden'">Dashboard</span>
        </a>

        <a href="<?= base_url('admin/businesses') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos(uri_string(), 'admin/business') === 0 ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
            <i class="fas fa-building w-5 text-center <?= strpos(uri_string(), 'admin/business') === 0 ? 'text-white' : 'text-gray-400' ?>"></i>
            <span :class="sidebarOpen ? 'block' : 'lg:hidden'">Businesses</span>
        </a>

        <a href="<?= base_url('admin/transactions') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos(uri_string(), 'admin/transaction') === 0 ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
            <i class="fas fa-credit-card w-5 text-center <?= strpos(uri_string(), 'admin/transaction') === 0 ? 'text-white' : 'text-gray-400' ?>"></i>
            <span :class="sidebarOpen ? 'block' : 'lg:hidden'">Transactions</span>
        </a>

        <div class="pt-4 mt-4 border-t border-gray-200">
            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                <i class="fas fa-sign-out-alt w-5 text-center text-gray-400"></i>
                <span :class="sidebarOpen ? 'block' : 'lg:hidden'">Logout</span>
            </a>
        </div>
    </nav>
</aside>
