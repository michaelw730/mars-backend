FROM php:7.4-alpine

COPY . /var/www/
WORKDIR /var/www/
EXPOSE 8080
CMD php -S 0.0.0.0:8080 -t public public/index.php