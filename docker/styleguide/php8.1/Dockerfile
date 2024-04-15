FROM php:8.1.28-fpm

ARG USER_ID
ARG GROUP_ID
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
COPY --from=composer:2.2.3 /usr/bin/composer /usr/local/bin/composer

# Set groups and user when on linux
RUN set -eux; \
    if [ "${USER_ID:-0}" -ne 0 ] && [ "${GROUP_ID:-0}" -ne 0 ]; then \
      userdel -f www-data; \
      if getent group www-data ; then groupdel www-data; fi; \
      groupadd -g "${GROUP_ID}" www-data; \
      useradd -l -u "${USER_ID}" -g www-data www-data; \
      install -d -m 0755 -o www-data -g www-data /home/www-data; \
    fi

COPY ./php-config/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./php-config/php.ini /usr/local/etc/php/conf.d/zzz_styleguide.ini

WORKDIR /var/www/html/${STYLEGUIDE_DIR}

RUN mkdir -p /var/www/html/${STYLEGUIDE_DIR}/var; \
    mkdir -p /var/www/html/${STYLEGUIDE_DIR}/vendor; \
    chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
