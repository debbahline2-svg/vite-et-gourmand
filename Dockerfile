FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
libssl-dev \
pkg-config \
libcurl4-openssl-dev \
zlib1g-dev \
&& pecl install mongodb \
&& docker-php-ext-enable mongodb \
&& docker-php-ext-install pdo pdo_mysql mysqli

COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]