FROM composer as builder
COPY composer.json composer.lock ./
COPY app app
COPY bootstrap bootstrap
COPY database database
COPY tests tests
RUN composer install \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

FROM php:7.1-apache
LABEL maintainer="Chrysovalantis Koutsoumpos <info@newweb.gr>"

ENV USER_ID=501
# Configure www-data user
RUN usermod -d /var/www -s /bin/bash -u ${USER_ID} www-data
RUN apt-get update && \
    docker-php-ext-install pdo pdo_mysql && \
    a2enmod rewrite

# Add application
WORKDIR /var/www
COPY server/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY --from=builder /app/vendor vendor

RUN mkdir -p var
COPY app app
COPY bootstrap bootstrap
COPY database database
COPY public public
COPY resources resources
COPY routes routes
COPY storage storage
COPY .env /.env
COPY composer.json composer.lock ./
COPY artisan ./