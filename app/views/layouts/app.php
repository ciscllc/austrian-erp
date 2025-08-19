<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Austrian ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <?php if (isset($_SESSION['user'])): ?>
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-blue-600">Austrian ERP</span>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="/dashboard" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <a href="/customers" class="text-gray-700 hover:text-blue-600">Kunden</a>
                        <a href="/products" class="text-gray-700 hover:text-blue-600">Produkte</a>
                        <a href="/orders" class="text-gray-700 hover:text-blue-600">Aufträge</a>
                    </div>
                    <div class="flex items-center">
                        <a href="/logout" class="text-red-600 hover:text-red-800">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    
    <main class="<?= isset($_SESSION['user']) ? 'py-8' : '' ?>">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?= $content ?>
        </div>
    </main>
</body>
</html>