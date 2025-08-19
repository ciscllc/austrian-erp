#!/bin/bash

# Docker Installation
print_status "Starte Docker Installation..."

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    print_status ".env Datei erstellt"
fi

# Build and start containers
print_status "Erstelle Docker Container..."
docker-compose up -d --build

# Wait for database
print_status "Warte auf Datenbank..."
sleep 30

# Run database setup
print_status "Richte Datenbank ein..."
docker-compose exec web php artisan migrate --force
docker-compose exec web php artisan db:seed --force

print_success "Docker Installation abgeschlossen!"
print_status "Anwendung läuft auf: http://localhost:8080"
print_status "phpMyAdmin: http://localhost:8081"