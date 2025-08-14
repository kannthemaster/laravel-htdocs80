#!/usr/bin/env bash
set -euo pipefail

# --- Composer setup (รองรับ private quota) ---
if [ -n "${GITHUB_TOKEN:-}" ]; then
  export COMPOSER_AUTH='{"github-oauth":{"github.com":"'"$GITHUB_TOKEN"'"}}'
fi
composer config -g github-protocols https
composer config platform.php 8.2.4

php -v
composer --version

# ติดตั้ง dependencies
if ! composer install --no-interaction --prefer-dist --no-progress; then
  composer update --no-interaction --prefer-dist --no-progress
fi

# --- Base .env (เฉยๆ เพื่อให้ artisan ใช้ได้ แต่ไม่บังคับต้องมี APP_KEY) ---
[ -f .env ] || cp .env.example .env

# --- สร้าง APP_KEY แบบไม่พึ่ง artisan และเขียน .env.testing ใหม่ทั้งไฟล์ ---
APP_KEY="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"

cat > .env.testing <<EOF
APP_ENV=testing
APP_DEBUG=true
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
APP_KEY=$APP_KEY
EOF

# สร้าง SQLite DB (ให้ไฟล์สะอาดทุกครั้ง)
mkdir -p database
: > database/database.sqlite

# --- รัน migration + tests; ถ้าล้มให้สคริปต์ล้มตาม ---
composer run pretest
composer test
