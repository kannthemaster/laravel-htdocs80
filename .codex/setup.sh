#!/usr/bin/env bash
set -euo pipefail

php -v
composer install --no-interaction --prefer-dist

cp .env.example .env || true
php artisan key:generate || true

# ensure APP_KEY in .env.testing
APP_KEY=$(php artisan key:generate --show)
if grep -q '^APP_KEY=' .env.testing 2>/dev/null; then
  sed -i 's/^APP_KEY=.*/APP_KEY='"$APP_KEY"'/' .env.testing
else
  printf "\nAPP_KEY=%s\n" "$APP_KEY" >> .env.testing
fi

mkdir -p database
: > database/database.sqlite

# enforce failures if tests fail
composer run pretest
composer test
