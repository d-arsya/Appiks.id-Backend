FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"

WORKDIR /app

RUN apt-get update && apt-get install -y \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

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

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]