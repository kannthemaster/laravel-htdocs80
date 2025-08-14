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

# ensure APP_KEY in .env.testing  (safe, no sed)
APP_KEY=$(php artisan key:generate --show)

if [ -f .env.testing ]; then
  APP_KEY="$APP_KEY" php -r '
    $f=".env.testing";
    $k=getenv("APP_KEY");
    $c=file_exists($f)?file_get_contents($f):"";
    if (preg_match("/^APP_KEY=/m",$c)) {
      $c=preg_replace("/^APP_KEY=.*/m","APP_KEY=".$k,$c);
    } else {
      $c=rtrim($c).PHP_EOL."APP_KEY=".$k.PHP_EOL;
    }
    file_put_contents($f,$c);
  '
else
  printf "APP_ENV=testing\nAPP_DEBUG=true\nCACHE_DRIVER=array\nSESSION_DRIVER=array\nQUEUE_CONNECTION=sync\nMAIL_MAILER=array\nDB_CONNECTION=sqlite\nDB_DATABASE=database/database.sqlite\nAPP_KEY=%s\n" "$APP_KEY" > .env.testing
fi


mkdir -p database
: > database/database.sqlite

# enforce failures if tests fail
composer run pretest
composer test
