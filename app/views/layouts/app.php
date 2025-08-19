<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Austrian ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .nav-link { @apply px-3 py-2 rounded-md text-sm font-medium; }
        .nav-link-active { @apply bg-blue-100 text-blue-700; }
        .nav-link:hover { @apply bg-gray-100 text-gray-900; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-blue-600">Austrian ERP</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/dashboard" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'nav-link-active' : '' ?>">Dashboard</a>
                    <a href="/customers" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/customers') !== false ? 'nav-link-active' : '' ?>">Kunden</a>
                    <a href="/products" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/products') !== false ? 'nav-link-active' : '' ?>">Produkte</a>
                    <a href="/orders" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/orders') !== false ? 'nav-link-active' : '' ?>">Aufträge</a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="text-sm text-gray-700">👤 <?= htmlspecialchars($_SESSION['user']['email']) ?></span>
                        <a href="/logout" class="text-red-600 hover:text-red-800 text-sm">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/dashboard" class="block px-3 py-2 rounded-md text-base font-medium <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'bg-blue-100 text-blue-700' : 'text-gray-700' ?>">Dashboard</a>
                <a href="/customers" class="block px-3 py-2 rounded-md text-base font-medium <?= strpos($_SERVER['REQUEST_URI'], '/customers') !== false ? 'bg-blue-100 text-blue-700' : 'text-gray-700' ?>">Kunden</a>
                <a href="/products" class="block px-3 py-2 rounded-md text-base font-medium <?= strpos($_SERVER['REQUEST_URI'], '/products') !== false ? 'bg-blue-100 text-blue-700' : 'text-gray-700' ?>">Produkte</a>
                <a href="/orders" class="block px-3 py-2 rounded-md text-base font-medium <?= strpos($_SERVER['REQUEST_URI'], '/orders') !== false ? 'bg-blue-100 text-blue-700' : 'text-gray-700' ?>">Aufträge</a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($_SESSION['flash']['error']) ?>
                </div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>
            
            <?= $content ?>
        </div>
    </main>
</body>
</html>