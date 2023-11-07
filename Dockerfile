# Dockerfile
FROM php:8.1-fpm-buster

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

