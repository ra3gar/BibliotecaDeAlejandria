# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Universidad UPED 2026 — Programación Aplicada 1. A library management system (BibliotecaDeAlejandria) built with Laravel 12.

## Commands

```bash
# First-time setup (install deps, generate key, migrate, build assets)
composer setup

# Start full dev environment (server + queue + logs + vite)
composer dev

# Run tests
composer test

# Run a single test file
php artisan test tests/Feature/ExampleTest.php

# Run a single test by name
php artisan test --filter=test_name

# Lint (only dirty files)
vendor/bin/pint --dirty

# Migrate and seed
php artisan migrate --seed

# Fresh migration
php artisan migrate:fresh --seed
```

## Stack

- **PHP** ^8.2, **Laravel** 12, **SQLite** (default for local dev)
- **Testing**: PHPUnit ^11.5.3 via `php artisan test` (in-memory SQLite, BCRYPT_ROUNDS=4)
- **Frontend**: Vite 7 + Tailwind CSS v4
- **Code style**: Laravel Pint

## MySQL Setup

In `.env`, configure:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_alejandria
DB_USERNAME=root
DB_PASSWORD=your_password
```

## Architecture

Laravel 12 standard MVC. Controllers are split into `Admin/` and `User/` namespaces under `app/Http/Controllers/`.

**Auth & Users**
- Table: `book_store_users` with standard auto-increment `id`
- Model: `App\Models\User` (not a custom model name — standard User with `$table = 'book_store_users'`)
- Fields: `first_name`, `last_name`, `email`, `password`, `role`
- Roles: `admin` | `user` (enum)
- Helper: `$user->isAdmin()`, accessor `$user->full_name`
- Role middleware alias `role` → `App\Http\Middleware\CheckRole`, registered in `bootstrap/app.php`
- `CheckRole` redirects wrong-role users to their correct home (admin→dashboard, user→catalogo)

**Domain Models**
- `User` — `book_store_users`, standard PK `id`
- `Category` — `categories`, standard PK `id`
- `Author` — `authors`, standard PK `id`
- `Book` — `books`, standard PK `id`; book cover images stored via `Storage::disk('public')` in `books/` folder
- `Loan` — `loans`, standard PK `id`; `status` enum: `active` | `returned` | `overdue`
- `book_author` — pivot table linking books ↔ authors (managed via `$book->authors()->sync()`)

**Routes**
- `GET /` → redirect to login
- `GET|POST /login` → `AuthController` (guest middleware)
- `POST /logout`
- Admin group (`auth` + `role:admin` + prefix `/admin` + name prefix `admin.`):
  - `GET /admin/dashboard` → `Admin\DashboardController`
  - Resources: `admin.users`, `admin.books`, `admin.categories`, `admin.authors`
- User group (`auth` + `role:user`):
  - `GET /catalogo` → `User\CatalogoController@index`
  - `GET /libros/{book}` → `User\CatalogoController@show` (named `books.show`)
  - `GET /perfil` → `User\ProfileController@index`

## Testing

Tests live in `tests/Feature/` and `tests/Unit/`. The test environment uses an in-memory SQLite DB — no `.env` changes needed to run tests.

Seed test users via `DatabaseSeeder`:
- `admin@biblioteca.com` / `password` → role: admin
- `juan@biblioteca.com` / `password` → role: user
