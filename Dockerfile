FROM dunglas/frankenphp:php8.2

USER root

# 1. Extensions PHP (mongodb en version 1.x)
RUN install-php-extensions mongodb-1.21.0 gd zip

# 2. Node.js pour compiler le CSS/JS
RUN apt-get update && apt-get install -y nodejs npm

# 3. Copier le projet
WORKDIR /app
COPY . .

# 4. Compiler les assets (Vite)
RUN npm install
RUN npm run build

# 5. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. Nettoyer les caches
RUN php artisan config:clear
RUN php artisan view:clear

# 7. Dossiers de stockage et permissions
RUN mkdir -p /app/storage/logs /app/storage/framework/sessions /app/storage/framework/views /app/storage/framework/cache /app/bootstrap/cache
RUN chmod -R 777 /app/storage /app/bootstrap/cache

# 8. Port pour Koyeb
ENV SERVER_NAME=":8000"
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
  CMD curl -f http://localhost:8000/ || exit 1

EXPOSE 8000
