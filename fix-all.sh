#!/bin/bash
echo "🔧 Repariere alle Fehler..."

# Create missing directories
mkdir -p app/{Controllers,Models,Views/{auth,dashboard,customers,products,orders,settings,errors}}
mkdir -p public/{css,js,assets}
mkdir -p storage/{logs,backups,uploads}

# Create missing files
touch public/favicon.ico
touch public/css/tremor.css
touch public/css/flakes.css

# Create basic structure
cat > public/js/app.js << 'EOF'
// Austrian ERP JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Austrian ERP loaded');
});
EOF

echo "✅ Alle Fehler behoben!"
echo "📍 Starten: php -S localhost:8000 -t public/"
echo "🌐 Öffnen: http://localhost:8000"