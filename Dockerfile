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

# 6. Nettoyer les caches pour être sûr
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan view:clear

# 7. Permissions (Lecture/Écriture pour le serveur)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# 8. Configurer le port
ENV SERVER_NAME=":8000"
EXPOSE 8000
