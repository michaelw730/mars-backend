FROM php:7.4-alpine

COPY App /var/www/App/
COPY public /var/www/public
COPY sql /var/www/sql/
COPY vendor /var/www/vendor

WORKDIR /var/www/
EXPOSE 8080
CMD php -S 0.0.0.0:8080 -t public public/index.php