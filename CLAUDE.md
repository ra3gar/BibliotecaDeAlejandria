# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Universidad UPED 2026 — Programación Aplicada 1. A library management system (BibliotecaDeAlejandria) built with Laravel 12.

## Commands

```bash
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

- **PHP** ^8.2, **Laravel** 12, **SQLite** (default)
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

Laravel 12 standard structure. Key conventions for this project:

**Auth & Users**
- Custom `usuarios` table with PK `id_usuario` INT and `contrasena` password field
- Roles: `usuario` | `administrador`
- Auth guard uses `App\Models\Usuario` (not the default `User`)
- Role middleware alias `role` → `App\Http\Middleware\CheckRole`, registered in `bootstrap/app.php`

**Domain Models** (planned, PK types noted)
- `Categoria` — PK `id_categoria` VARCHAR
- `Autor` — PK `id_autor` INT
- `Libro` — PK `id_libro` VARCHAR
- `Usuario` — PK `id_usuario` INT
- `Prestamo` — PK `id_prestamo`
- `libro_autor` — pivot table

**Routes**
- `GET /` → redirect to login
- `GET|POST /login` → `AuthController`
- `POST /logout`
- `GET /catalogo` → role:usuario middleware
- `GET /dashboard` → role:administrador middleware

**Fortify** (when installed): set `features=[]` and `views=false` to avoid route conflicts with custom auth.

## Testing

Tests live in `tests/Feature/` and `tests/Unit/`. The test environment uses an in-memory SQLite DB — no `.env` changes needed to run tests.

Seed test users via `DatabaseSeeder`:
- `admin@uped.edu` / `admin123` → rol: administrador
- `juan@uped.edu` / `usuario123` → rol: usuario
