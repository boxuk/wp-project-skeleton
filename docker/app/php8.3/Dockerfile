FROM php:8.3.21-fpm

ARG USER_ID
ARG GROUP_ID
ARG XDEBUG_VERSION

# Do all apt-get installs on one line so they are installed on the same layer
RUN set -eux; \
    apt-get -y update; \
    apt-get -y install git less zip unzip zlib1g-dev sendmail libicu-dev g++ libpng-dev libzip-dev libmagickwand-dev msmtp --no-install-recommends; \
    apt-get clean && apt-get autoremove; \
    rm -rf /var/lib/apt/lists/*; \
    pecl install imagick && docker-php-ext-enable imagick; \
    pecl install pcov && docker-php-ext-enable pcov; \
    pecl install xdebug-${XDEBUG_VERSION} && docker-php-ext-enable xdebug; \
    docker-php-ext-configure intl; \
    docker-php-ext-install -j"$(nproc)" mysqli opcache zip intl gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# WP-CLI - https://hub.docker.com/_/wordpress/tags?page=1&name=cli
COPY --from=wordpress:cli-php8.3 /usr/local/bin/wp /usr/local/bin/wp

# Set groups and user when on linux
RUN set -eux; \
    if [ "${USER_ID:-0}" -ne 0 ] && [ "${GROUP_ID:-0}" -ne 0 ]; then \
      userdel -f www-data; \
      if getent group www-data ; then groupdel www-data; fi; \
      groupadd -g "${GROUP_ID}" www-data; \
      useradd -l -u "${USER_ID}" -g www-data www-data; \
      install -d -m 0755 -o www-data -g www-data /home/www-data; \
    fi

COPY ./php-config/*.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www
EXPOSE 9000
CMD ["php-fpm"]
