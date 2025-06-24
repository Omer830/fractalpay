# Base image with PHP 8.2 FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies (including procps for ps/top)
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor \
    nginx \
    nodejs \
    npm \
    postgresql-client \
    libpq-dev \
    procps \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm ci && npm run build

# Fix permissions for Laravel
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy Supervisor configuration
COPY ./Docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy NGINX configuration
COPY ./Docker/nginx.conf /etc/nginx/nginx.conf

# Ensure required runtime directories for Supervisor exist
RUN mkdir -p /var/run /var/log/supervisor

# Expose internal NGINX port
EXPOSE 80

# Start Supervisor (managing php-fpm, nginx, Laravel queue workers)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
