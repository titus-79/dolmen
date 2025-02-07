FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    wget \
    ${PHPIZE_DEPS}

RUN docker-php-ext-install pdo pdo_mysql zip

RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configuration pour rendre les variables d'environnement accessibles à PHP
RUN echo "variables_order = \"EGPCS\"" > /usr/local/etc/php/conf.d/variables_order.ini

RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Création des répertoires nécessaires
RUN mkdir -p /var/log/php /var/lib/php/sessions \
    && chown -R www-data:www-data /var/log/php /var/lib/php/sessions \
    && chmod -R 755 /var/log/php \
    && chmod 1733 /var/lib/php/sessions

# Configuration PHP personnalisée
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN wget -O /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-9.phar \
    && chmod +x /usr/local/bin/phpunit

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

## Création du répertoire des sessions tôt dans le build
#RUN mkdir -p /var/lib/php/sessions \
#    && chown -R www-data:www-data /var/lib/php/sessions \
#    && chmod 1733 /var/lib/php/sessions

# Copie et installation des dépendances
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copie des fichiers de configuration
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Copie du reste des fichiers du projet
COPY . .

# Copie du .env s'il existe
COPY .env* ./

# Définition des permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Génération de l'autoloader optimisé
RUN composer dump-autoload --optimize