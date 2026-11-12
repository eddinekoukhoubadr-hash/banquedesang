FROM dunglas/frankenphp:php8.2

# 1. Installer les extensions PHP
RUN install-php-extensions mongodb gd zip

# 2. Installer Node.js pour compiler le CSS/JS
RUN apt-get update && apt-get install -y nodejs npm

# 3. Copier le projet
WORKDIR /app
COPY . .

# 4. Compiler les assets (Vite)
RUN npm install
RUN npm run build

# 5. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# 6. Nettoyer les caches
RUN php artisan config:clear
RUN php artisan view:clear

# 7. Créer TOUS les dossiers de stockage et forcer les permissions
RUN mkdir -p /app/storage/logs /app/storage/framework/sessions /app/storage/framework/views /app/storage/framework/cache /app/bootstrap/cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 777 /app/storage /app/bootstrap/cache

# 8. Configurer le port pour Koyeb
ENV SERVER_NAME=":8000"
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 CMD curl -f http://localhost:8000/ || exit 1
EXPOSE 8000
