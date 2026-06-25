FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip sockets bcmath pcntl \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --optimize-autoloader --no-scripts 2>/dev/null || \
    composer install --no-interaction --optimize-autoloader

COPY . .

RUN composer dump-autoload --optimize
RUN php artisan key:generate --force 2>/dev/null || true
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["./entrypoint.sh"]
