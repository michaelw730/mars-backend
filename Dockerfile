FROM php:7.4-alpine

COPY App /var/www/
COPY public /var/www/
COPY sql /var/www/
COPY vendor /var/www/


WORKDIR /var/www/
EXPOSE 8080
CMD php -S 0.0.0.0:8080 -t public public/index.php