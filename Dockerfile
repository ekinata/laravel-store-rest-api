# Use the official PHP image with the necessary extensions
FROM php:8.2

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libmcrypt-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY .env.docker .env

# Expose port 8000 and start PHP server
EXPOSE 8000

CMD ["sh","startup.sh"]
