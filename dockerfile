FROM php:8.1-fpm

WORKDIR /var/www/html

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql

CMD ["php", "-S", "0.0.0.0:3000", "-t", "/var/www/html"]
