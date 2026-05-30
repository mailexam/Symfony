FROM php:8.2-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV HTTP_HOST=0.0.0.0
ENV HTTP_PORT=8000

EXPOSE 8000

CMD ["sh", "-c", "php -S ${HTTP_HOST}:${HTTP_PORT} -t public"]
