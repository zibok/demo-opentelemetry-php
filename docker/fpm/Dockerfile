FROM php:8.3-fpm

# Install PHP
RUN apt-get update \
    && apt-get install -qy libpq-dev libxml2-dev \
    && docker-php-ext-install opcache pdo pdo_pgsql xml \
    && rm -rf /var/lib/apt/lists

COPY fpm-www.conf /etc/php/8.3/fpm/pool.d/www.conf

#RUN apt-get update \
#    && apt-get install -qy libz-dev \
#    && pecl install opentelemetry protobuf \
#    && echo "extension=opentelemetry.so" > /usr/local/etc/php/conf.d/opentelemetry.ini \
#    && echo "extension=protobuf.so" > /usr/local/etc/php/conf.d/protobuf.ini \
#    && rm -rf /var/lib/apt/lists
