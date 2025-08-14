#!/usr/bin/env bash
set -euo pipefail

php -v
composer install --no-interaction --prefer-dist

# base env for app & testing
cp .env.example .env || true
php artisan key:generate || true

# ensure testing env has key too
APP_KEY=$(php artisan key:generate --show)
if grep -q '^APP_KEY=' .env.testing 2>/dev/null; then
  sed -i 's/^APP_KEY=.*/APP_KEY='"$APP_KEY"'/' .env.testing
else
  echo "APP_KEY=$APP_KEY" >> .env.testing
fi

# sqlite testing DB + fresh migrate + tests
mkdir -p database
: > database/database.sqlite
php artisan migrate:fresh --env=testing --force
php artisan test --stop-on-failure || true
