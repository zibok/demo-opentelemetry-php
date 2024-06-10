FROM php:8.3-fpm

# Install PHP
RUN apt-get update \
    && apt-get install -qy libxml2-dev \
    && docker-php-ext-install xml

COPY fpm-www.conf /etc/php/8.3/fpm/pool.d/www.conf
