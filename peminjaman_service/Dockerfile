FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    postgresql-dev \
    libzip-dev \
    $PHPIZE_DEPS \
    linux-headers \
    && docker-php-ext-install pdo pdo_pgsql zip sockets bcmath pcntl \
    && apk del $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV PHP_CLI_SERVER_WORKERS=4

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --optimize-autoloader --no-scripts 2>/dev/null || \
    composer install --no-interaction --optimize-autoloader

COPY . .

RUN composer dump-autoload --optimize
RUN php artisan key:generate --force 2>/dev/null || true
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod +x entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["./entrypoint.sh"]
