FROM php:8.3-fpm

# Install dependencies sistem untuk ekstensi PHP & utilities
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer resmi dari image composer:latest
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy konfigurasi php custom
COPY ./docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory ke backend
WORKDIR /var/www/backend

# Berikan izin ke www-data
RUN chown -R www-data:www-data /var/www

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
