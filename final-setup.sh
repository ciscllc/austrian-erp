#!/bin/bash

echo "🚀 Vervollständige Austrian ERP Installation..."

# Create directory structure
mkdir -p app/Controllers
mkdir -p app/Models
mkdir -p app/Views/auth
mkdir -p app/Views/dashboard
mkdir -p app/Views/customers
mkdir -p app/Views/products
mkdir -p app/Views/orders
mkdir -p app/Views/settings
mkdir -p app/Views/errors
mkdir -p storage/logs
mkdir -p public/css
mkdir -p public/js
mkdir -p public/assets

# Create favicon.ico
touch public/favicon.ico

# Create basic CSS
cat > public/css/app.css << 'EOF'
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}
EOF

# Create basic JS
cat > public/js/app.js << 'EOF'
console.log('Austrian ERP loaded');
EOF

echo "✅ Installation vervollständigt!"
echo "📍 Starten Sie mit: php -S localhost:8000 -t public/"
echo "🌐 Öffnen Sie: http://localhost:8000"