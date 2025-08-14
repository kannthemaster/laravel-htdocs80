# Laravel 8 – Agent Guide
Setup:
- composer install --no-interaction --prefer-dist
- cp .env.example .env && php artisan key:generate
- ensure .env.testing has APP_KEY (use `php artisan key:generate --show`)
- create database/database.sqlite; run `php artisan migrate:fresh --env=testing --force`
Run:
- Tests: `php artisan test --stop-on-failure`
## Test workflow
- Reset DB and run all tests:
  - `composer run pretest && composer test`
- One-off local run:
  - `php artisan migrate:fresh --env=testing --force && php artisan test --stop-on-failure`
