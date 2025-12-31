# Use a high-performance image with PHP and Nginx pre-installed
FROM richarvey/php-nginx-alpine:latest

# Set the working directory
WORKDIR /var/www/html

# Copy the entire project code
COPY . .

# Set up environment variables for the image
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV SKIP_COMPOSER 0
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install Node.js and NPM to build your assets (Laravel Mix)
# We do this inside the Docker image so you don't have to upload your local node_modules
RUN apk add --no-cache nodejs npm

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build your CSS and JS assets (Laravel Mix)
RUN npm install && npm run prod

# Set correct folder permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Render uses 80 by default)
EXPOSE 80
