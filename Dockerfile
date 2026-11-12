FROM php:8.2-apache

# 1. Installer les extensions
RUN apt-get update && apt-get install -y libzip-dev libpng-dev unzip libssl-dev pkg-config \
    && docker-php-ext-install zip gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# 2. Activer mod_rewrite
RUN a2enmod rewrite

# 3. Copier le projet dans le dossier par défaut d'Apache
WORKDIR /var/www/html
COPY . .

# 4. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# 5. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 6. LA MAGIE : Modifier la config d'Apache juste avant de le démarrer (Port 80 et Dossier public)
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 7. On garde le port 80 pour Koyeb
EXPOSE 80
