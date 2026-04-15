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

ENV APP_ENV="prod"
ENV APP_SECRET="***REMOVED***"
ENV DATABASE_URL="postgresql://vite_et_gourmand_db_user:***REMOVED***@dpg-d7fv34dckfvc73dap1i0-a/vite_et_gourmand_db?serverVersion=16&charset=utf8&sslmode=require"

WORKDIR /app
COPY . .

RUN composer install --ignore-platform-reqs --no-scripts --optimize-autoloader --no-interaction

RUN echo "" > .env && rm -f .env.local .env.prod .env.local.php

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/"]
