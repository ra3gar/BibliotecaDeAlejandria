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

- **PHP** ^8.2, **Laravel** 12, **MySQL** (conexión local activa)
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
- Model: `App\Models\User` (standard User with `$table = 'book_store_users'`)
- Fields: `first_name`, `last_name`, `email`, `password`, `role`, `is_active`
- Roles: `admin` | `user` (enum)
- Helpers: `$user->isAdmin()`, `$user->isActive()`, accessor `$user->full_name`
- `is_active` (boolean, default true) — cuentas inactivas son desconectadas automáticamente por `CheckRole`
- Role middleware alias `role` → `App\Http\Middleware\CheckRole`, registered in `bootstrap/app.php`
- `CheckRole` bloquea usuarios inactivos (logout + redirect a login con mensaje), y redirige roles incorrectos a su home

**Static Assets**
- `public/images/logo.png` — logo del sistema; usado en login, sidebar admin y navbar usuario via `asset('images/logo.png')`
- `public/images/Fondo.png` — fondo de la pantalla de login
- `public/images/Fondo2.jpg` — fondo del panel admin y catálogo de usuario
- Estas imágenes NO usan `Storage::disk` — se sirven directamente desde `public/`

**Domain Models**
- `User` — `book_store_users`, standard PK `id`
- `Category` — `categories`, standard PK `id`
- `Author` — `authors`, standard PK `id`; fotos en `Storage::disk('public')` carpeta `authors/`
  - Campos: `first_name`, `last_name`, `bio` (text), `photo_path` (string, nullable)
  - Accessor: `$author->photo_url` → URL pública de la foto, o `null` si no tiene
  - Al actualizar foto: se elimina la anterior del disco antes de guardar la nueva
  - Al eliminar autor: se elimina su foto del disco automáticamente
- `Book` — `books`, standard PK `id`; portadas en `Storage::disk('public')` carpeta `books/`
  - Campos: `title`, `isbn`, `summary`, `publisher`, `category_id`, `book_cover`, `published_at`, `año`, `stock_total`, `available_copies`
  - `año` (integer, nullable) — se deriva automáticamente de `published_at` en `BookController` al crear/editar; no se acepta desde el formulario
  - Stock físico: `stock_total` (int, default 0), `available_copies` (int, default 0)
  - Helper: `$book->isAvailable()` → true si `available_copies > 0`
  - Observer: `BookObserver` registrado en `AppServiceProvider` — graba en `audit_logs` cada created/updated/deleted
  - Campos eliminados: `codigo_interno` y `path_pdf` (removidos en migración `2026_03_15_000001`)
- `Loan` — `loans`, standard PK `id`; `status` enum: `active` | `returned` | `overdue`
  - Crear préstamo: verifica `available_copies > 0`, decrementa en 1
  - Marcar devuelto: incrementa `available_copies` en 1; ignora si ya estaba devuelto
- `AuditLog` — `audit_logs`, sin `updated_at`; campos: `user_id`, `action`, `model_type`, `model_id`, `description`
- `book_author` — pivot table linking books ↔ authors (via `$book->authors()->sync()`)

**Fotos de autores — reglas de negocio**
- Formatos aceptados: `jpeg`, `jpg`, `png`; máximo 2 MB (validación en `AuthorController`)
- Ruta de almacenamiento: `storage/app/public/authors/`; accesible vía `Storage::disk('public')`
- `AuthorController@destroy` limpia el archivo del disco al eliminar el autor
- Vista pública `books.show` muestra foto circular + biografía de cada autor del libro; si no hay foto, muestra un avatar SVG de placeholder

**Stock físico — reglas de negocio**
- `available_copies` no puede superar `stock_total` (regla `lte:stock_total` en BookController)
- Préstamo solo se crea si `available_copies > 0`; error de validación si no hay stock
- `markReturned` protegido contra doble ejecución

**Auditoría**
- Tabla `audit_logs`: registra automáticamente creación, edición y eliminación de libros (vía `BookObserver`) e intentos de login fallidos (vía `AuthController`)
- El `BookObserver` captura el usuario autenticado (`Auth::id()`) en cada evento
- `AuthController@login` graba `action='login_failed'`, `model_type='Auth'`, con descripción que incluye el email y la IP del intento
- Los últimos 8 registros (de cualquier tipo) se muestran en el dashboard del admin
- Acciones posibles: `created`, `updated`, `deleted`, `login_failed`

**Routes**
- `GET /` → redirect to login
- `GET|POST /login` → `AuthController` (guest middleware)
- `POST /logout`
- Admin group (`auth` + `role:admin` + prefix `/admin` + name prefix `admin.`):
  - `GET /admin/dashboard` → `Admin\DashboardController`
  - Resources: `admin.users`, `admin.books`, `admin.categories`, `admin.authors`
  - `admin.loans` → index, show, destroy, create, store
  - `PATCH /admin/loans/{loan}/return` → `Admin\LoanController@markReturned` (named `admin.loans.return`)
  - `PATCH /admin/users/{user}/toggle-active` → `Admin\UserController@toggleActive` (named `admin.users.toggle-active`)
  - `PATCH /admin/users/{user}/change-password` → `Admin\UserController@changePassword` (named `admin.users.change-password`)
- User group (`auth` + `role:user`):
  - `GET /catalogo` → `User\CatalogoController@index`
  - `GET /catalogo/categoria/{category}` → `User\CatalogoController@byCategory` (named `catalogo.categoria`)
  - `GET /catalogo/autor/{author}` → `User\CatalogoController@byAuthor` (named `catalogo.autor`)
  - `GET /libros/{book}` → `User\CatalogoController@show` (named `books.show`)
  - `GET /perfil` → `User\ProfileController@index`

## Testing

Tests live in `tests/Feature/` and `tests/Unit/`. The test environment uses an in-memory SQLite DB — no `.env` changes needed to run tests.

Seed test users via `DatabaseSeeder`:
- `admin@biblioteca.com` / `password` → role: admin, is_active: true
- `juan@biblioteca.com` / `password` → role: user, is_active: true
