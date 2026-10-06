FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    cron \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    bcmath \
    dom \
    simplexml \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN printf '%s\n' \
    '* * * * * cd /var/www/html && /usr/local/bin/php artisan schedule:run >> /proc/1/fd/1 2>/proc/1/fd/2' \
    | crontab -

WORKDIR /var/www/html

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]