#!/bin/bash

# Exit immediately on error
set -e

echo "==> Starting FractalPay deployment..."

# Load environment (especially for NVM)
source ~/.bashrc || true
source ~/.nvm/nvm.sh || true

# Put application into maintenance mode
php artisan down || true

# Pull latest changes from Git
echo "==> Pulling latest code..."
git checkout public/build
git pull origin main

# Install PHP dependencies
echo "==> Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js dependencies
echo "==> Installing Node.js dependencies..."
npm ci

# Build assets
echo "==> Building frontend assets..."
npm run build

# Run database migrations
echo "==> Running database migrations..."
php artisan migrate --force

# Seed database seeding
echo "==> Running database seeding..."
php artisan db:seed
php artisan module:seed --all --force

# Bring the application back up
php artisan down
php artisan up
php artisan down
php artisan up

# Restart supervisor
sudo supervisorctl restart fractalpay-worker:*

# Restart nginx
sudo service nginx restart
echo "==> FractalPay deployment complete!"
exit 0
