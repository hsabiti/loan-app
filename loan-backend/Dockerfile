FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    netcat-openbsd \
    unzip \
    libzip-dev \
    zip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install
