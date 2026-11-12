FROM php:8.2-apache

# Installer les extensions PHP requises (MongoDB, GD, Zip, etc.)
RUN apt-get update && apt-get install -y libzip-dev libpng-dev unzip libssl-dev pkg-config \
    && docker-php-ext-install zip gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite
RUN sed -i 's/\/var\/www\/html/\/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf

# Changer le port d'écoute vers 8000 (pour Koyeb)
RUN sed -i 's/Listen 80/Listen 8000/g' /etc/apache2/ports.conf

# Autoriser l'accès au dossier public
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
RUN echo '<Directory /var/www/html/public>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# Copier le projet
WORKDIR /var/www/html
COPY . .

# Installer Composer et les dépendances
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000
