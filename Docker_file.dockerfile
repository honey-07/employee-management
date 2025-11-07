# Use the official PHP image
FROM php:8.1-apache

# Copy all project files into the Apache web directory
COPY . /var/www/html/

# Enable Apache mod_rewrite (important for routes)
RUN a2enmod rewrite

# Expose the default Apache port
EXPOSE 80
