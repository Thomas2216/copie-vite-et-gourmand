FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev libssl-dev git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql opcache \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --ignore-platform-reqs --no-scripts --optimize-autoloader --no-interaction

RUN rm -f .env.local.php

EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:8080 -t public/"]
