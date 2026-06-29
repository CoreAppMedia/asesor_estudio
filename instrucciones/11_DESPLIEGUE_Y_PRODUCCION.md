# Guía de Despliegue y Producción

Este documento especifica el proceso detallado para desplegar la plataforma **Asesor de Estudios de Matemáticas CCH - UNAM** en un entorno de producción, garantizando su seguridad, optimización y estabilidad.

---

## 1. Requisitos del Servidor

- **Sistema Operativo:** Ubuntu Server 22.04 LTS (o similar compatible con Linux).
- **Servidor Web:** Nginx (Recomendado) o Apache.
- **Motor de Base de Datos:** MySQL 8.0+ o MariaDB 10.5+.
- **PHP:** Versión 8.2 o superior con las siguientes extensiones habilitadas:
  - `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `sqlite3`, `curl`.
- **Node.js:** Versión 18.x o superior con `npm`.

---

## 2. Preparación del Código en el Servidor

1. **Clonar el Repositorio:**
   ```bash
   cd /var/www
   git clone <URL_DEL_REPOSITORIO> asesor_estudio
   cd asesor_estudio
   ```

2. **Instalar Dependencias de PHP (Laravel):**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Configurar el Archivo de Entorno (.env):**
   Copiar la plantilla de entorno y editarla con los datos reales de producción:
   ```bash
   cp .env.example .env
   nano .env
   ```
   **Variables clave para Producción:**
   ```env
   APP_NAME="Asesor de Estudios de Matemáticas CCH"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tu-dominio.cch.unam.mx

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_db_produccion
   DB_USERNAME=usuario_db_produccion
   DB_PASSWORD=contrasena_segura

   CACHE_STORE=file
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   ```

4. **Generar la Clave de la Aplicación:**
   ```bash
   php artisan key:generate --force
   ```

---

## 3. Preparación de la Base de Datos

1. **Ejecutar Migraciones y Cargar Catálogos Iniciales:**
   Este comando configurará las tablas definitivas y cargará las 170 preguntas iniciales estructuradas del temario oficial:
   ```bash
   php artisan migrate --force --seed
   ```

---

## 4. Compilación del Frontend (React + Vite)

1. **Instalar Dependencias de JS:**
   ```bash
   npm ci
   ```

2. **Compilar para Producción:**
   Vite generará los archivos finales optimizados en la carpeta `public/build/`:
   ```bash
   npm run build
   ```

---

## 5. Permisos de Directorios

Es fundamental que el servidor web tenga los permisos apropiados para escribir en las carpetas de caché y logs:

```bash
# Asumir que el servidor usa el usuario 'www-data'
sudo chown -R www-data:www-data /var/www/asesor_estudio
sudo find /var/www/asesor_estudio -type f -exec chmod 644 {} \;
sudo find /var/www/asesor_estudio -type d -exec chmod 755 {} \;

# Dar permisos de escritura a las carpetas requeridas por Laravel
sudo chmod -R 775 /var/www/asesor_estudio/storage
sudo chmod -R 775 /var/www/asesor_estudio/bootstrap/cache
```

---

## 6. Configuración del Servidor Web (Nginx)

Crea un archivo de configuración para Nginx en `/etc/nginx/sites-available/asesor_estudio`:

```nginx
server {
    listen 80;
    server_name tu-dominio.cch.unam.mx;
    root /var/www/asesor_estudio/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Habilita el sitio y reinicia Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/asesor_estudio /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 7. Optimización de Laravel para Producción

En producción, Laravel debe utilizar caché para evitar leer archivos de configuración y rutas en cada petición:

```bash
# Cachear archivos de configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas Blade
php artisan view:cache
```

*Nota: Ejecuta `php artisan clear-compiled` o limpia la caché si haces modificaciones posteriores al archivo `.env` o a las rutas.*

---

## 8. Seguridad y Certificados (SSL/HTTPS)

Se recomienda utilizar Let's Encrypt para generar el certificado SSL de forma automática y gratuita:

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d tu-dominio.cch.unam.mx
```

Certbot actualizará la configuración de Nginx automáticamente para forzar la redirección a HTTPS y renovar los certificados de forma periódica.
