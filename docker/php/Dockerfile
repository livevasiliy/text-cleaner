# Dockerfile
FROM php:8.3-fpm-alpine

# Установим системные зависимости и PHP расширения
RUN apk add --no-cache \
        bash \
        git \
        unzip \
        icu-dev \
        zlib-dev \
        libxml2-dev \
        libzip-dev \
        oniguruma-dev \
        curl \
        shadow

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions intl opcache zip xml mbstring ctype redis


# Установим Composer (в /usr/local/bin)
COPY --from=composer:2.7 /usr/bin/composer /usr/local/bin/composer

# Копируем конфигурацию php.ini (если нужна)
COPY ./docker/php/conf.d /usr/local/etc/php/conf.d

# Установим пользователя www-data без пароля и добавим права
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www

USER www-data

EXPOSE 9000
CMD ["php-fpm"]