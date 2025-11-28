# From official PHP with Apache server image
FROM php:8.2-apache

# Default working dir of the web server
WORKDIR /var/www/html/

# Install MySQLi + pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy files into container
COPY . /var/www/html/

# Give ownership to Apache for /uplaods folder
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

RUN chown -R www-data:www-data /var/www/html/uploads