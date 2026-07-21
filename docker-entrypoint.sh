#!/bin/sh
set -e

# Cachear configuración, rutas y vistas para producción
echo "Optimizando la caché de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones y seeders de forma segura en producción
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "Ejecutando seeders de base de datos..."
php artisan db:seed --force

# Iniciar el proceso principal del contenedor (apache2-foreground)
echo "Iniciando Apache..."
exec "$@"
