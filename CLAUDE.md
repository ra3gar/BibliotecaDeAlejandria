# CLAUDE.md — Guía Maestra del Proyecto

> Archivo de instrucciones para Claude Code (claude.ai/code).
> Define el contexto, la arquitectura y las reglas de negocio del sistema.

---

## Proyecto

**Biblioteca de Alejandría** — Sistema de gestión de biblioteca física con préstamos híbridos (web + presencial + QR).
Desarrollado para la materia **Programación Aplicada 1** — Universidad UPED 2026.

---

## Comandos esenciales

```bash
# Instalación completa desde cero (deps, clave, migraciones, assets)
composer setup

# Arrancar el entorno completo (servidor + queue + vite con hot reload)
composer dev

# Ejecutar todas las pruebas automatizadas
composer test

# Ejecutar un archivo de prueba específico
php artisan test tests/Feature/ExampleTest.php

# Ejecutar una prueba por nombre
php artisan test --filter=nombre_del_test

# Formatear código (solo archivos modificados)
vendor/bin/pint --dirty

# Migrar y sembrar datos de prueba
php artisan migrate --seed

# Reiniciar la base de datos desde cero (¡elimina todo!)
php artisan migrate:fresh --seed
```

---

## Stack tecnológico

| Capa | Tecnología | Versión |
|---|---|---|
| Backend | PHP + Laravel | ^8.2 / 12 |
| Base de datos | MySQL (desarrollo) | 8.0 |
| Base de datos | SQLite en memoria (pruebas) | — |
| Frontend | Vite + Tailwind CSS v4 + Alpine.js | 7 / v4 / 3.x |
| Tipografía | Inter (sans) + Playfair Display (serif) | Google Fonts |
| QR | simplesoftwareio/simple-qrcode | ^4.2 |
| Testing | PHPUnit via `php artisan test` | ^11.5.3 |
| Linter | Laravel Pint | — |

> Las pruebas usan SQLite en memoria con `BCRYPT_ROUNDS=4`. No requieren cambios en `.env`.

### Sistema de diseño — Tailwind CSS v4

Los tokens de diseño se definen en `resources/css/app.css` mediante el bloque `@theme {}`.
El tema se llama **Códice Antiguo** y usa la paleta:

| Token de utilidad | Variable CSS | Valor |
|---|---|---|
| `mahogany-{50..950}` | `--color-mahogany-*` | Caoba oscuro — fondos de sidebar y navbar |
| `gold-{300..700}` | `--color-gold-*` | Oro ámbar — botones primarios y acentos |
| `parchment-{50..500}` | `--color-parchment-*` | Pergamino — fondos de cards y páginas |
| `sepia-{300..700}` | `--color-sepia-*` | Tierra — texto secundario y labels |
| `midnight-{100..900}` | `--color-midnight-*` | Azul medianoche — badges y acciones neutras |

**Alias semánticos** (definidos en `:root`, no en `@theme` para evitar conflictos con namespaces reservados de Tailwind):

```css
--color-background: #FAF6EE;   /* parchment-100 — fondo general */
--color-primary:    #1C1410;   /* mahogany-900  — texto principal */
--color-accent:     #C9974A;   /* gold-500      — acciones destacadas */
--color-text-main:  #3C2E22;   /* sepia-700     — cuerpo de texto */
```

Uso en Blade: `bg-(--color-background)`, `text-(--color-primary)`, etc.

**Componentes CSS reutilizables** (en `@layer components`):
`.btn-primary`, `.btn-secondary`, `.btn-ghost`, `.btn-danger`, `.form-input`, `.form-label`, `.card`, `.card-parchment`

**Animaciones**: `.lib-animate` (fade-up individual) y `.lib-stagger` (entrada escalonada para grids)

---

## Configuración MySQL

En el archivo `.env` de la raíz del proyecto:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_alejandria
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

---

## Arquitectura del sistema

Laravel 12 MVC estándar. Los controladores están divididos en dos namespaces bajo `app/Http/Controllers/`:

- `Admin/` — panel de administración (CRUD completo + dashboard + auditoría)
- `User/` — portal del usuario (catálogo, reservas, perfil)

---

## Autenticación y usuarios

- **Tabla**: `book_store_users` (nombre personalizado para evitar colisión con otros proyectos Laravel)
- **Modelo**: `App\Models\User` con `$table = 'book_store_users'`
- **Campos**: `first_name`, `last_name`, `email`, `password`, `role`, `is_active`, `birth_date`
- **Roles**: `admin` | `user` (ENUM)
- **Helpers del modelo**:
  - `$user->isAdmin()` → true si `role = 'admin'`
  - `$user->isActive()` → true si `is_active = 1`
  - `$user->age()` → edad en años (int) o `null` si `birth_date` es NULL
  - `$user->full_name` → accesor que devuelve `"Nombre Apellido"`
- **`is_active`** (boolean, default true) — las cuentas inactivas son desconectadas automáticamente por `CheckRole`
- **`birth_date`** (date, nullable) — usada para validar restricciones de edad en préstamos
- **Middleware de rol**: alias `role` → `App\Http\Middleware\CheckRole`, registrado en `bootstrap/app.php`
- `CheckRole` bloquea usuarios inactivos (hace logout + redirige a login con mensaje) y redirige roles incorrectos a su home

---

## Recursos estáticos

Ubicados en `public/images/` — se sirven **directamente** (no usan `Storage::disk`):

| Archivo | Uso en el sistema |
|---|---|
| `logo.png` | Logo en login, sidebar admin y navbar usuario |
| `Fondo.png` | Imagen de fondo de la pantalla de login |
| `Fondo2.jpg` | Fondo del panel admin y catálogo de usuario |

Referencia en Blade: `{{ asset('images/archivo.ext') }}`

---

## Modelos de dominio

### `User` — Usuarios (`book_store_users`)
PK estándar `id`. Ver sección de autenticación para campos y helpers.

### `Category` — Categorías (`categories`)
PK estándar `id`. Un libro pertenece a una categoría (nullable con `SET NULL ON DELETE`).

### `Author` — Autores (`authors`)
PK estándar `id`. Fotos en `Storage::disk('public')` carpeta `authors/`.
- Campos: `first_name`, `last_name`, `bio` (text), `photo_path` (string, nullable)
- Accesor: `$author->photo_url` → URL pública si tiene foto, `null` si no
- Al **actualizar** foto: se elimina la anterior del disco antes de guardar la nueva
- Al **eliminar** autor: su foto se elimina del disco automáticamente
- Formatos aceptados: `jpeg`, `jpg`, `png` | Máximo: 2 MB

### `Book` — Libros (`books`)
PK estándar `id`. Portadas en `Storage::disk('public')` carpeta `books/`.
- Campos: `title`, `isbn`, `summary`, `publisher`, `category_id`, `book_cover`, `published_at`, `año`, `stock_total`, `available_copies`, `min_age`
- `año` (int, nullable) — se deriva automáticamente de `published_at` en `BookController`; **el formulario no lo envía**
- `stock_total` — total de ejemplares físicos en la biblioteca
- `available_copies` — ejemplares disponibles para préstamo ahora mismo
- `min_age` (int, default 0) — edad mínima para reservar el libro; `0` = sin restricción
- Helper: `$book->isAvailable()` → true si `available_copies > 0`
- Observer: `BookObserver` registrado en `AppServiceProvider` — graba en `audit_logs` cada `created`, `updated`, `deleted`
- Campos **eliminados** en migración `2026_03_15_000001`: `codigo_interno` y `path_pdf`

### `Loan` — Préstamos (`loans`)
PK estándar `id`. Implementa el flujo híbrido presencial + web con código QR.
- `status` ENUM: `pending` | `active` | `returned` | `overdue`
- `qr_token` (string, unique, nullable) — UUID generado con `Str::uuid()` al crear la reserva
- `loan_date` — fecha de la reserva online
- `return_date` — se rellena solo al marcar el préstamo como devuelto

**Flujo completo**:
```
[1] Usuario reserva online  →  status = 'pending'  +  stock--  +  qr_token generado
[2] Usuario muestra QR en la biblioteca
[3] Admin confirma entrega  →  status = 'active'   +  audit_log
[4] Admin marca devuelto    →  status = 'returned' +  stock++   +  audit_log
[E] Incumplimiento          →  status = 'overdue'  (manual, sin movimiento de stock)
```

**Reglas al crear un préstamo**:
- Verifica que `available_copies > 0` (error si no hay stock)
- Si `min_age > 0` y el usuario tiene `birth_date`, verifica la edad
- Si el usuario no tiene `birth_date`, la validación de edad se **omite** (no bloquea)
- Guard anti-duplicados: no se puede tener dos reservas `pending` o `active` del mismo libro

- `confirmPickup` — cambia `pending → active`; graba en `audit_logs`
- `markReturned` — incrementa `available_copies`; protegido contra doble ejecución; graba en `audit_logs`
- El usuario puede auto-reservar desde la vista de detalle del libro (`POST /libros/{book}/reservar`)

### `AuditLog` — Auditoría (`audit_logs`)
Sin `updated_at` por diseño: los logs son **inmutables**.
- Campos: `user_id`, `action`, `model_type`, `model_id`, `description`
- Acciones posibles: `created`, `updated`, `deleted`, `login_failed`

### `book_author` — Pivote libros ↔ autores
Tabla intermedia sin `id` propio ni timestamps. Se gestiona con `$book->authors()->sync([...])`.

---

## Reglas de negocio — Stock físico

- `available_copies` **no puede superar** `stock_total` (regla `lte:stock_total` en `BookController`)
- El préstamo solo se crea si `available_copies > 0`
- El stock **decrementa** al crear la reserva (`pending`), **no** al confirmar la entrega
- `markReturned` protegido contra doble ejecución (verifica estado antes de modificar)

---

## Generación de QR

- Package: `simplesoftwareio/simple-qrcode` v4 — alias de facade: `QrCode`
- Genera SVG inline con: `{!! QrCode::size(N)->generate($url) !!}`
- El QR codifica la URL directa a `admin.loans.show` del préstamo
- Solo se muestra al usuario mientras `status === 'pending'`

---

## Auditoría del sistema

| Evento | Disparador | Descripción |
|---|---|---|
| `created` | `BookObserver` | Al crear un libro |
| `updated` | `BookObserver` | Al editar un libro |
| `deleted` | `BookObserver` | Al eliminar un libro |
| `updated` | `LoanController@confirmPickup` | Cambio `pending → active` |
| `updated` | `LoanController@markReturned` | Cambio a `returned` |
| `login_failed` | `AuthController@login` | Intento fallido (guarda email + IP) |

- `BookObserver` captura el usuario autenticado con `Auth::id()`
- Los últimos **8 registros** se muestran en el dashboard del admin

---

## Rutas

```
GET  /                    → redirect al login

GET|POST /login           → AuthController       (middleware: guest)
POST     /logout          → AuthController

── Grupo admin ─── middleware: auth + role:admin | prefijo: /admin | nombre: admin. ──

GET    /admin/dashboard                          → Admin\DashboardController
resources admin.users, admin.books, admin.categories, admin.authors (CRUD completo)
GET|POST /admin/loans (index, show, destroy, create, store)
PATCH  /admin/loans/{loan}/return                → Admin\LoanController@markReturned
PATCH  /admin/loans/{loan}/confirm-pickup        → Admin\LoanController@confirmPickup
PATCH  /admin/users/{user}/toggle-active         → Admin\UserController@toggleActive
PATCH  /admin/users/{user}/change-password       → Admin\UserController@changePassword

── Grupo usuario ─── middleware: auth + role:user ──

GET  /catalogo                                   → User\CatalogoController@index
GET  /catalogo/categoria/{category}              → User\CatalogoController@byCategory
GET  /catalogo/autor/{author}                    → User\CatalogoController@byAuthor
GET  /libros/{book}                              → User\CatalogoController@show
POST /libros/{book}/reservar                     → User\LoanController@store
GET  /perfil                                     → User\ProfileController@index
```

---

## Pruebas automatizadas

Las pruebas viven en `tests/Feature/` y `tests/Unit/`. El entorno de prueba usa SQLite en memoria — no se necesitan cambios en `.env`.

Usuarios sembrados por `DatabaseSeeder`:

| Email | Contraseña | Rol | Estado |
|---|---|---|---|
| `admin@biblioteca.com` | `password` | admin | activo |
| `user@biblioteca.com` | `password` | user | activo |
