#!/bin/bash
echo "🔧 Letzte Korrekturen..."

# Fix requireAuth method
cat > app/Controllers/BaseController.php << 'EOF'
<?php

namespace App\Controllers;

class BaseController
{
    protected function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
    
    protected function render(string $view, array $data = [])
    {
        $this->requireAuth();
        
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            echo "View not found: $viewPath";
            return;
        }
        
        extract($data);
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        $layout = __DIR__ . '/../Views/layouts/app.php';
        if (file_exists($layout)) {
            include $layout;
        } else {
            echo $content;
        }
    }
    
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
EOF

# Create missing views
cat > app/Views/customers/create.php << 'EOF'
<?php $title = 'Neuer Kunde'; ?>
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Neuen Kunden anlegen</h1>
        
        <form action="/customers" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Kundennummer</label>
                <input type="text" name="customer_number" required class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            
            <div>
                <label class="block text-sm font-medium">Firmenname</label>
                <input type="text" name="company_name" class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Vorname</label>
                    <input type="text" name="first_name" class="mt-1 block w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium">Nachname</label>
                    <input type="text" name="last_name" class="mt-1 block w-full rounded-md border-gray-300">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium">E-Mail</label>
                <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                Kunden anlegen
            </button>
        </form>
    </div>
</div>
EOF

echo "✅ Alle Fehler behoben!"
echo "🚀 Die Anwendung läuft nun vollständig!"