FROM php:8.4-fpm

WORKDIR /var/www/html

ARG UID=1000
ARG GID=1000
RUN groupmod -o -g ${GID} www-data && usermod -o -u ${UID} -g ${GID} www-data

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    nodejs \
    npm \
    netcat-traditional \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --- Cache-friendly dependency install ---
COPY ecom/backend/composer.json ecom/backend/composer.lock ./
RUN composer install --no-interaction --no-dev --no-scripts --no-autoloader --prefer-dist

COPY ecom/backend/package.json ecom/backend/package-lock.json* ./
RUN npm install

# --- Copy the rest of the Laravel app ---
COPY ecom/backend/ /var/www/html/

# Finalize composer (autoloader + package:discover) and build assets
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && npm run build

EXPOSE 9000

USER www-data

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]