FROM php:8.0.19-fpm

ARG STYLEGUIDE_DIR

RUN set -eux; \
    apt-get -y update; \
    apt-get -y install git libzip-dev zip unzip --no-install-recommends; \
    rm -rf /var/lib/apt/lists/*; \
    docker-php-ext-configure zip; \
    docker-php-ext-install pdo_mysql opcache zip; \
    pecl install apcu; \
    docker-php-ext-enable apcu

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY ./php-config/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./php-config/php.ini /usr/local/etc/php/conf.d/zzz_styleguide.ini

WORKDIR /var/www/html/${STYLEGUIDE_DIR}

RUN mkdir -p /var/www/html/${STYLEGUIDE_DIR}/var; \
    chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
