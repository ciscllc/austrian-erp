#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "Bitte nicht als root ausführen!"
   exit 1
fi

# Check prerequisites
print_status "Prüfe Voraussetzungen..."

commands=("php" "composer" "mysql" "npm" "git")
missing_commands=()

for cmd in "${commands[@]}"; do
    if ! command -v $cmd &> /dev/null; then
        missing_commands+=($cmd)
    fi
done

if [ ${#missing_commands[@]} -ne 0 ]; then
    print_error "Fehlende Befehle: ${missing_commands[*]}"
    print_status "Installation der fehlenden Befehle:"
    
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        echo "Ubuntu/Debian: sudo apt install ${missing_commands[*]}"
        echo "CentOS/RHEL: sudo yum install ${missing_commands[*]}"
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        echo "macOS: brew install ${missing_commands[*]}"
    fi
    
    exit 1
fi

# Check PHP version
php_version=$(php -r "echo PHP_VERSION_ID;")
if [ $php_version -lt 80000 ]; then
    print_error "PHP 8.0 oder höher wird benötigt"
    exit 1
fi

print_success "Alle Voraussetzungen erfüllt"

# Download and extract the application
print_status "Lade Anwendung herunter..."

if [ ! -d "austrian-erp" ]; then
    git clone https://github.com/yourusername/austrian-erp.git austrian-erp
fi

cd austrian-erp

# Make installer executable
chmod +x install.php

# Run PHP installer
print_status "Starte Installation..."
php install.php

print_success "Installation abgeschlossen!"
print_status "Starte Anwendung..."
php -S localhost:8000 -t public/