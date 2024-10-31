FROM alpine:3.18

ARG ALPINE_VERSION=3.18

LABEL Maintainer="Morteza Fathi <mortezaa.fathi@gmail.com>" \
      Description="Lightweight container with Nginx 1.24 & PHP-FPM 8.2 based on Alpine Linux."

RUN echo https://mirrors.pardisco.co/alpine/v$ALPINE_VERSION/main > /etc/apk/repositories
RUN echo https://mirrors.pardisco.co/alpine/v$ALPINE_VERSION/community >> /etc/apk/repositories

# Install packages and remove default server definition
RUN apk add --no-cache php82 \
    php82-common \
    php82-fpm \
    php82-pdo \
    php82-opcache \
    php82-zip \
    php82-phar \
    php82-iconv \
    php82-cli \
    php82-curl \
    php82-openssl \
    php82-mbstring \
    php82-tokenizer \
    php82-fileinfo \
    php82-json \
    php82-xml \
    php82-xmlwriter \
    php82-xmlreader \
    php82-simplexml \
    php82-dom \
    php82-pdo_pgsql \
    php82-pdo_mysql \
    php82-pdo_sqlite \
    php82-pecl-redis \
    php82-posix \
    php82-pcntl \
    php82-bcmath \
    php82-ctype \
    php82-gmp \
    php82-gd \
    php82-zlib \
    php82-intl \
    php82-ctype \
    php82-exif \
    php82-soap \
    php82-sockets


RUN apk add --no-cache nginx \
    supervisor \
    curl \
    tzdata \
    nano \
    git \
    git-flow \
    vim \
    htop \
    nodejs \
    npm

RUN ln -s /usr/bin/php82 /usr/bin/php

# Install PHP tools
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# Configure nginx
COPY .docker/dev/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY .docker/dev/config/fpm-pool.conf /etc/php82/php-fpm.d/www.conf
COPY .docker/dev/config/php.ini /etc/php82/conf.d/custom.ini

# Configure supervisord
COPY .docker/dev/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN set -x \
	&& adduser -u 1000 -D -S -G www-data www-data

# Setup document root
RUN mkdir -p /var/www/html

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R www-data.www-data /var/www/html && \
  chown -R www-data.www-data /run && \
  chown -R www-data.www-data /var/lib/nginx && \
  chown -R www-data.www-data /var/log/nginx

# Switch to use a non-root user from here on
USER www-data

# Add application
WORKDIR /var/www/html
COPY --chown=www-data ./ /var/www/html/

RUN chmod 777 -R storage/ \
 && chmod 777 -R bootstrap/cache/ \
 && chmod 755 .docker/dev/docker-entrypoint.sh

RUN composer config http-basic.nova.laravel.com "farhad.eizakshiri@gmail.com" "7nnAS2DvcUnJi1PHjpoysdecUf9N7LIW8iSEhwoQ02VysMBj19"
RUN composer install --no-autoloader

# Expose the port nginx is reachable on
EXPOSE 8080

ENTRYPOINT ["./.docker/dev/docker-entrypoint.sh"]
