FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
libssl-dev \
pkg-config \
libcurl4-openssl-dev \
zlib1g-dev \
&& pecl install mongodb \
&& docker-php-ext-enable mongodb \
&& docker-php-ext-install pdo pdo_mysql mysqli \
&& a2dismod mpm_event mpm_worker mpm_prefork \
&& a2enmod mpm_prefork

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html