FROM php:8.3-cli

# Install PHP
RUN apt update && \
    apt install -yq \
      curl \
      git \
      libpq-dev \
      unzip \
    && \
    rm -rf /var/lib/apt/lists

RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer, Symfony
RUN curl -sS https://getcomposer.org/installer | php -- --2 --install-dir=/usr/local/bin --filename=composer \
    && curl -L https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony-cli_linux_amd64.tar.gz -o /tmp/symfony-cli_linux_amd64.tar.gz \
    && tar xzf /tmp/symfony-cli_linux_amd64.tar.gz -C /usr/local/bin symfony \
    && rm -f /tmp/symfony-cli_linux_amd64.tar.gz

RUN useradd -m -u1001 -U -s /bin/bash dev

# Install Node JS
ENV NODE_VERSION=22
ENV npm_config_cache=/tmp
RUN (curl -sL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - ) && apt-get install -y nodejs

RUN apt-get update \
    && apt-get install -qy libz-dev \
    && MAKEFLAGS="-j $(nproc)" pecl install opentelemetry grpc \
    && echo "extension=opentelemetry.so" > /usr/local/etc/php/conf.d/opentelemetry.ini \
    && echo "extension=grpc.so" > /usr/local/etc/php/conf.d/grpc.ini \
    && rm -rf /var/lib/apt/lists

USER dev
