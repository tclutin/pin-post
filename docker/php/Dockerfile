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
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers \
    pkgconf \
    brotli-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

RUN docker-php-ext-install pcntl

RUN pecl install openswoole \
    && docker-php-ext-enable openswoole

RUN echo "openswoole.use_shortname=Off" > /usr/local/etc/php/conf.d/openswoole.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
