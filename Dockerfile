# Production Dockerfile for Smart Recipe Application
FROM php:8.2-apache

# Install MySQL extension and dependencies
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

# Configure Apache VirtualHost with Alias for /smart-recipes so all legacy & local paths work seamlessly
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    Alias /smart-recipes /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Adjust permissions for web server
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
