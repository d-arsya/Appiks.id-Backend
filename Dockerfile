FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . /app 

RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    tokenizer \
    intl \
    pcntl \
    bcmath \
    exif \
    gd \
    zip

RUN echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 128M" >> /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install
RUN php artisan storage:link || true