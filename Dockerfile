# Use the official PHP image as a base image
FROM php:7.4-apache

# Set the working directory
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
