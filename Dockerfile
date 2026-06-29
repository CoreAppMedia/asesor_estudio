# ==========================================
# Etapa 1: Compilación del Frontend (Node)
# ==========================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copiar archivos de dependencias de node
COPY package*.json ./

# Instalar dependencias de node
RUN npm ci

# Copiar todo el código para compilar
COPY . .

# Compilar recursos de Vite para producción
RUN npm run build

# ==========================================
# Etapa 2: Imagen de PHP de Producción (Apache)
# ==========================================
FROM php:8.3-apache AS production

# Instalar dependencias del sistema y extensiones de PHP necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring gd exif opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar Apache DocumentRoot para apuntar a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar el módulo de reescritura de Apache (mod_rewrite) para Laravel .htaccess
RUN a2enmod rewrite

# Configurar Apache para que escuche en el puerto dinámico asignado por Render ($PORT)
ENV PORT=10000
RUN sed -s -i -e "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf
RUN sed -s -i -e "s/<VirtualHost \*:80>/<VirtualHost *:\${PORT}>/" /etc/apache2/sites-available/*.conf

# Configurar límites de carga de archivos (para imágenes de cámara u otros archivos)
RUN echo "upload_max_filesize=25M\npost_max_size=30M\nmemory_limit=256M" \
    > /usr/local/etc/php/conf.d/uploads.ini

# Configurar Opcache para rendimiento óptimo en producción
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar el código del proyecto con los permisos del usuario web de Apache (www-data)
COPY --chown=www-data:www-data . .

# Copiar los assets compilados por Node desde la primera etapa (node-builder)
COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

# Instalar dependencias de PHP con Composer para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Generar enlace simbólico de storage
RUN rm -rf public/storage && php artisan storage:link

# Dar permisos a storage y bootstrap/cache (necesarios para que Laravel escriba logs y caché)
RUN chmod -R 775 storage bootstrap/cache

# Puerto que expone la aplicación
EXPOSE ${PORT}

# Comando de arranque seguro: limpiar caché, cachear para producción,
# ejecutar migraciones sin borrar datos (usando migrate --force) y arrancar Apache
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    apache2-foreground
