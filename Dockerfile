# Utiliser l'image PHP 8.2
FROM php:8.2-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Copier le code de l'application Laravel
COPY . /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Exécuter Composer pour installer les dépendances
RUN composer install --optimize-autoloader --no-dev

# Exposer le port 9000
EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm"]
