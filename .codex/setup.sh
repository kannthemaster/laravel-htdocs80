#!/usr/bin/env bash
set -euo pipefail

php -v

# ใช้ token (ถ้ามี) เพื่อดึงแพ็กเกจได้
if [ -n "${GITHUB_TOKEN:-}" ]; then
  export COMPOSER_AUTH='{"github-oauth":{"github.com":"'"$GITHUB_TOKEN"'"}}'
fi

composer config -g github-protocols https

# 👇 ตรึงแพลตฟอร์มให้ Composer แก้ dependencies ด้วย PHP 8.2.4
composer config platform.php 8.2.4

# ติดตั้ง deps; ถ้า install ยังติดข้อจำกัด ให้ลอง update (ครั้งเดียวใน sandbox)
if ! composer install --no-interaction --prefer-dist --no-progress; then
  composer update --no-interaction --prefer-dist --no-progress
fi

# base env
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
