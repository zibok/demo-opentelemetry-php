FROM php:8.3-cli

# Install PHP
RUN apt update && \
    apt install -yq \
      curl \
      git \
      unzip \
    && \
    rm -rf /var/lib/apt/lists

# Install Composer, Symfony
RUN curl -sS https://getcomposer.org/installer | php -- --2 --install-dir=/usr/local/bin --filename=composer \
    && curl -L https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony-cli_linux_amd64.tar.gz -o /tmp/symfony-cli_linux_amd64.tar.gz \
    && tar xzf /tmp/symfony-cli_linux_amd64.tar.gz -C /usr/local/bin symfony \
    && rm -f /tmp/symfony-cli_linux_amd64.tar.gz

RUN useradd -m -u1001 -U -s /bin/bash dev

USER dev
