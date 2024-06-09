FROM debian:bookworm-slim

# Install PHP
RUN apt update && \
    apt install -yq \
       apt-transport-https \
       curl \
       lsb-release \
    && \
    curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list && \
    apt update && \
    apt install -yq \
      git \
      php8.3-cli \
      php8.3-xml \
      php8.3-zip \
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
