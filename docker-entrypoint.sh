#!/bin/sh
set -e

# 1. Ejecutar migraciones y seeders primero para asegurar que existan las tablas necesarias (como "cache" o "sessions")
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "Ejecutando seeders de base de datos..."
php artisan db:seed --force

# 2. Cachear configuración, rutas y vistas para producción
echo "Optimizando la caché de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar el proceso principal del contenedor (apache2-foreground)
echo "Iniciando Apache..."
exec "$@"
