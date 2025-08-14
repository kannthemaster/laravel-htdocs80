# Laravel 8 – Agent Guide
Setup:
- composer install --no-interaction --prefer-dist
- cp .env.example .env && php artisan key:generate
- ensure .env.testing has APP_KEY (use `php artisan key:generate --show`)
- create database/database.sqlite; run `php artisan migrate:fresh --env=testing --force`
Run:
- Tests: `php artisan test --stop-on-failure`
