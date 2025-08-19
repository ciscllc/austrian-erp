#!/bin/bash

echo "Vervollständige Installation..."

# Create directory structure
mkdir -p app/Controllers
mkdir -p app/Models
mkdir -p app/Views/layouts
mkdir -p app/Views/auth
mkdir -p app/Views/dashboard
mkdir -p app/Views/customers
mkdir -p app/Views/errors
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

# Create basic views
cat > app/Views/layouts/app.php << 'EOF'
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Austrian ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <main class="min-h-screen flex items-center justify-center">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <?= $content ?>
        </div>
    </main>
</body>
</html>
EOF

cat > app/Views/dashboard/index.php << 'EOF'
<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h1 class="text-3xl font-bold text-gray-900">Willkommen bei Austrian ERP</h1>
        <p class="mt-2 text-sm text-gray-600">Ihr revisionssicheres ERP-System</p>
    </div>
</div>
EOF

cat > app/Views/errors/404.php << 'EOF'
<div class="text-center">
    <h1 class="text-6xl font-bold text-gray-900">404</h1>
    <p class="mt-2 text-xl text-gray-600">Seite nicht gefunden</p>
    <a href="/" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
        Zurück zur Startseite
    </a>
</div>
EOF

echo "Installation vervollständigt! Starten Sie mit: php -S localhost:8000 -t public/"