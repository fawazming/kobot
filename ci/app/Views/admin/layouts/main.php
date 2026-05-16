<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> | KoboTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active { background-color: rgb(37 99 235); color: white; }
        .sidebar-link.active i { color: white; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">
        <?= view('admin/components/sidebar') ?>

        <div class="flex-1 flex flex-col overflow-hidden" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
            <?= view('admin/components/header') ?>

            <main class="flex-1 overflow-y-auto p-6">
                <?= view('admin/components/alerts') ?>
                <?= $this->renderSection('content') ?>
            </main>

            <?= view('admin/components/footer') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</body>
</html>
