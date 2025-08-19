#!/bin/bash
echo "🔧 Repariere Views..."

# Create all missing views
mkdir -p app/Views/{auth,dashboard,customers,products,orders,settings,errors}

# Create all missing view files
cat > app/Views/auth/login.php << 'EOF'
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Austrian ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Austrian ERP Login</h2>
            </div>
            <form class="mt-8 space-y-6" action="/login" method="POST">
                <div>
                    <label>E-Mail</label>
                    <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label>Passwort</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Anmelden
                </button>
            </form>
        </div>
    </div>
</body>
</html>
EOF

cat > app/Views/products/index.php << 'EOF'
<?php $title = 'Produkte'; ?>
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Produkte</h1>
                <a href="/products/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Neues Produkt
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900">Noch keine Produkte</h3>
                <p class="text-gray-500 mt-2">Erstellen Sie Ihr erstes Produkt.</p>
                <a href="/products/create" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Produkt anlegen
                </a>
            </div>
        </div>
    </div>
</div>
EOF

cat > app/Views/orders/index.php << 'EOF'
<?php $title = 'Aufträge'; ?>
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Aufträge</h1>
                <a href="/orders/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Neuer Auftrag
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900">Noch keine Aufträge</h3>
                <p class="text-gray-500 mt-2">Erstellen Sie Ihren ersten Auftrag.</p>
                <a href="/orders/create" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Auftrag erstellen
                </a>
            </div>
        </div>
    </div>
</div>
EOF

cat > app/Views/errors/404.php << 'EOF'
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Seite nicht gefunden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-900">404</h1>
            <p class="mt-2 text-xl text-gray-600">Seite nicht gefunden</p>
            <a href="/" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Zurück zur Startseite
            </a>
        </div>
    </div>
</body>
</html>
EOF

echo "✅ Alle Views erstellt!"
echo "🚀 Starten Sie mit: php -S localhost:8000 -t public/"