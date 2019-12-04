FROM php:7.4

RUN apt-get update && apt-get install -y git vim curl unzip && \
    docker-php-ext-install opcache && \
    useradd nginx

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer config -g repositories.packagist composer https://packagist.jp && \
    composer global require hirak/prestissimo
    
WORKDIR /var/www

CMD bash