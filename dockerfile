FROM php:8.1-apache

# Install necessary PHP extensions
RUN apt-get update && \
    apt-get install -y libpq-dev libcurl4-openssl-dev && \
    docker-php-ext-install pdo_pgsql pgsql && \
    docker-php-ext-install curl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your application files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html
