FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

COPY src .

RUN apk add --no-cache postgresql-dev

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

RUN addgroup -g 1000 symfony && adduser -G symfony -g symfony -s /bin/sh -D symfony

USER symfony