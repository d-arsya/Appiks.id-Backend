FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80" \
    PHP_UPLOAD_MAX_FILESIZE=15M \
    PHP_POST_MAX_SIZE=15M

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

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install
RUN php artisan storage:link || true