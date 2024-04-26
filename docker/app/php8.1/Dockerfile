FROM php:8.1.28-fpm

ARG USER_ID
ARG GROUP_ID
ARG INSTALL_XDEBUG
ARG XDEBUG_VERSION

# Do all apt-get installs on one line so they are installed on the same layer
RUN set -eux; \
    apt-get -y update; \
    apt-get -y install git less zip unzip zlib1g-dev libicu-dev g++ libpng-dev libzip-dev libmagickwand-dev --no-install-recommends; \
    apt-get clean && apt-get autoremove; \
    rm -rf /var/lib/apt/lists/*; \
    pecl install memcache-8.0 && docker-php-ext-enable memcache; \
    pecl install imagick && docker-php-ext-enable imagick; \
    [ "${INSTALL_XDEBUG:-false}" = true ] && pecl install xdebug-${XDEBUG_VERSION} && docker-php-ext-enable xdebug; \
    docker-php-ext-configure intl; \
    docker-php-ext-install -j"$(nproc)" mysqli opcache zip intl gd

# Composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# WP-CLI - https://hub.docker.com/_/wordpress/tags?page=1&name=cli
COPY --from=wordpress:cli-2.6.0-php8.1 /usr/local/bin/wp /usr/local/bin/wp

# MhSendmail
RUN set -eux; \
    curl -sSLo /usr/bin/mhsendmail https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64; \
    chmod ugo+x /usr/bin/mhsendmail

# Blackfire
# https://blackfire.io/docs/integrations/docker/php-docker
RUN set -eux; \
    version="$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;")" \
    && architecture="$(uname -m)" \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s "https://blackfire.io/api/v1/releases/probe/php/linux/$architecture/$version" \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so "$(php -r "echo ini_get ('extension_dir');")/blackfire.so" \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8307\n" > "$PHP_INI_DIR/conf.d/blackfire.ini" \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz;

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
