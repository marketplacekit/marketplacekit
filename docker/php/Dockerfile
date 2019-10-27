FROM php:7.3-fpm-alpine

RUN apk --update add \
    alpine-sdk \
    openssl-dev \
    php7-pear \
    php7-dev \
    supervisor \
    hiredis-dev \
    libevent \
    libevent-dev \
    imagemagick-dev \
    zlib-dev \
    libmemcached \
    libmemcached-dev \
    gmp \
    gmp-dev \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql gmp

RUN pecl install imagick \
    && docker-php-ext-enable imagick \
    && pecl install memcached-3.1.3 \
    && docker-php-ext-enable memcached \
    && pecl clear-cache

WORKDIR /var/www/html/app/

CMD ["php-fpm"]
