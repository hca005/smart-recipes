# Production Dockerfile for Smart Recipe Application
FROM php:8.2-apache

# Install MySQL extension and dependencies
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Adjust permissions for web server
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
