#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

git reset --hard HEAD  # Reset local changes
# Pull the latest version of the app
git pull origin main --rebase --autostash --quiet

# Install composer dependencies
composer update --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan cache:clear
php artisan clear-compiled
php artisan artisan storage:link

# Recreate cache
php artisan optimize

# Compile npm assets
npm run build

# Run database migrations
php artisan migrate --force

# Exit maintenance mode
php artisan up
php artisan config:clear

echo "Deployment finished!"
