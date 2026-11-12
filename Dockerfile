FROM dunglas/frankenphp:php8.2

# 1. Installer les extensions PHP requises (MongoDB, GD, Zip, etc.)
RUN install-php-extensions mongodb gd zip

# 2. Copier le projet
WORKDIR /app
COPY . .

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# 4. Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# 5. Dire à FrankenPHP d'écouter sur le port 8000 (attendu par Koyeb)
ENV SERVER_NAME=":8000"

# 6. Exposer le port (pour la clarté)
EXPOSE 8000
