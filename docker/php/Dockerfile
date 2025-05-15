FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
