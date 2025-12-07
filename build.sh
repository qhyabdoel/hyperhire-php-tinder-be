#!/usr/bin/env bash
# exit on error
set -o errexit

composer install
composer dump-autoload
php artisan migrate --force
php artisan key:generate