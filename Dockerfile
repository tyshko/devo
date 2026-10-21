# Dockerfile
FROM php:8.2.35-fpm-bookworm
RUN printf "session.upload_progress.enabled = Off\n" > /usr/local/etc/php/conf.d/zz-security.ini

# Встановлення залежностей
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    git \
    unzip

# Встановлення розширень PHP
RUN docker-php-ext-install pdo pdo_mysql zip

# Встановлення Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

