FROM php:8.3-fpm

# Install PHP
RUN apt-get update \
    && apt-get install -qy libpq-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql xml \
    && rm -rf /var/lib/apt/lists

COPY fpm-www.conf /etc/php/8.3/fpm/pool.d/www.conf
