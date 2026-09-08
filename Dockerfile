FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    zlib1g-dev \
    unzip \
    git \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

WORKDIR /var/www/html

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]