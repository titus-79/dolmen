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


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN wget -O /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-9.phar \
    && chmod +x /usr/local/bin/phpunit

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

COPY composer.json .
#COPY composer.json /var/www/html/dolmen/

RUN composer install --no-scripts --no-autoloader

# Copie du reste des fichiers du projet
COPY . .

# Génération de l'autoloader optimisé
RUN composer dump-autoload --optimize