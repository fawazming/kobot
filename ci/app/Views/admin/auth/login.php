<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KoboTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-bold text-2xl">KT</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">KoboTrack</h1>
            <p class="text-gray-500 mt-1">Payment Verification Platform</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <?php if (session()->has('error')): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"><?= session('error') ?></div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <?php foreach (session('errors') as $error): ?>
                    <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"><?= $error ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm"><?= session('success') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="username" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="Enter username">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                placeholder="Enter password">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-gray-400">
                <p>Default: admin / admin123</p>
            </div>
        </div>
    </div>
</body>
</html>
