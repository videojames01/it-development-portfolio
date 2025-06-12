# Install official php apache image
FROM php:8.3.21-apache

# Working directory (default apache document root)
WORKDIR /var/www/html

# Apache rewrite module used for routing
RUN a2enmod rewrite

# Making apache serve the public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update the apache config so that the changes take effect
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Installing packages and extensions for php, composer, laravel, and mysql
RUN apt-get update && apt-get install -y \
    nodejs npm unzip libzip-dev libonig-dev libxml2-dev git default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath

# Installing composer
COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer

# Changes ownership so apache has permission to rewrite
RUN chown -R www-data:www-data /var/www/html

# Expose port 80, tells Docker that container listens on port 80
EXPOSE 80

# Starts apache in foreground so that container keeps running
CMD ["apache2-foreground"]
