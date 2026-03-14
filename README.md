# Biblioteca de Alejandría

Sistema de gestión de biblioteca física desarrollado como proyecto universitario para la materia **Programación Aplicada 1** — Universidad UPED 2026.

---

## Tecnologías utilizadas

| Tecnología | Versión | Descripción |
|---|---|---|
| **PHP** | ^8.2 | Lenguaje de programación del backend |
| **Laravel** | 12 | Framework principal del proyecto (MVC, ORM, rutas, middleware) |
| **Blade** | — | Motor de plantillas de Laravel para las vistas |
| **MySQL** | — | Base de datos activa para desarrollo local (configurable en `.env`) |
| **SQLite** | — | Base de datos en memoria para pruebas automatizadas |
| **Tailwind CSS** | v4 | Framework de estilos utilitarios para el frontend |
| **Vite** | 7 | Herramienta de bundling y compilación de activos frontend |
| **PHPUnit** | ^11.5 | Framework de pruebas automatizadas |
| **Laravel Pint** | — | Herramienta de formato y estilo de código PHP |

---

## Requisitos

- PHP ^8.2
- Composer
- MySQL (servidor local)
- Node.js (para Vite/Tailwind)
- Git

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd BibliotecaDeAlejandria

# 2. Instalar dependencias, generar clave de aplicación y migrar
composer setup

# 3. Configurar la base de datos en .env
#    (ver conexionabd.txt para referencia rápida)
```

Acceder en el navegador a: `http://localhost:8000`

---

## Comandos de desarrollo

```bash
# Arrancar el entorno completo (servidor + queue + logs + vite)
composer dev

# Solo compilar assets (producción)
npm run build

# Ejecutar pruebas
composer test

# Migrar y sembrar datos de prueba
php artisan migrate --seed

# Reiniciar base de datos desde cero
php artisan migrate:fresh --seed

# Limpiar caché de configuración
php artisan config:clear && php artisan cache:clear

# Verificar enlace de almacenamiento público
php artisan storage:link
```

---

## Usuarios de prueba

| Email | Contraseña | Rol |
|---|---|---|
| admin@biblioteca.com | password | admin |
| juan@biblioteca.com | password | user |

---

## Estructura del proyecto

```
BibliotecaDeAlejandria/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php   # KPIs, préstamos recientes, auditoría
│   │   │   │   ├── BookController.php        # CRUD + stock físico + campos extendidos
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── AuthorController.php      # CRUD + gestión de fotos
│   │   │   │   ├── LoanController.php        # Registro, devolución y control de stock
│   │   │   │   └── UserController.php        # CRUD + activar/desactivar + cambiar contraseña
│   │   │   ├── User/
│   │   │   │   ├── CatalogoController.php    # Catálogo, filtros por categoría y autor
│   │   │   │   └── ProfileController.php
│   │   │   └── AuthController.php            # Login, logout + auditoría de fallos
│   │   └── Middleware/
│   │       └── CheckRole.php                 # Verifica rol y estado activo del usuario
│   ├── Models/
│   │   ├── User.php          # is_active, isAdmin(), isActive(), full_name
│   │   ├── Book.php          # stock_total, available_copies, año, codigo_interno, path_pdf
│   │   ├── Category.php
│   │   ├── Author.php        # photo_path, photo_url accessor, bio
│   │   ├── Loan.php          # Scopes de filtrado, relaciones user/book
│   │   └── AuditLog.php      # Registro de auditoría (libros + intentos de login)
│   ├── Observers/
│   │   └── BookObserver.php  # Graba en audit_logs al crear/editar/eliminar libros
│   └── Providers/
│       └── AppServiceProvider.php  # Registra BookObserver
│
├── database/
│   └── migrations/
│       ├── ..._create_users_table.php
│       ├── ..._create_categories_table.php
│       ├── ..._create_authors_table.php
│       ├── ..._create_books_table.php
│       ├── ..._create_book_author_table.php
│       ├── ..._create_loans_table.php
│       ├── 2026_03_14_000001_add_stock_to_books_table.php
│       ├── 2026_03_14_000002_add_is_active_to_book_store_users_table.php
│       ├── 2026_03_14_000003_create_audit_logs_table.php
│       ├── 2026_03_14_000004_add_photo_path_to_authors_table.php
│       └── 2026_03_14_000005_add_fields_to_books_table.php  # año, codigo_interno, path_pdf
│
├── resources/views/
│   ├── layouts/         # admin.blade.php, app.blade.php
│   ├── auth/            # login.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php          # KPIs, préstamos recientes, auditoría
│   │   ├── books/                       # index, create, edit, _form
│   │   ├── loans/                       # index, show, create
│   │   ├── users/                       # index, create, edit
│   │   ├── categories/
│   │   └── authors/
│   └── user/
│       ├── catalogo.blade.php
│       ├── catalogo-filtrado.blade.php
│       ├── book-detail.blade.php
│       └── profile.blade.php
│
├── routes/web.php
├── conexionabd.txt          # Referencia rápida de configuración de BD
├── GUIAS/
│   └── ConexionaBD.txt      # Documentación técnica completa de la BD
└── composer.json
```

---

## Módulos implementados

### Gestión de libros

| Campo | Descripción |
|---|---|
| `title` | Título del libro (requerido) |
| `isbn` | ISBN (único, opcional) |
| `codigo_interno` | Código de identificación interno de la biblioteca (único, opcional) |
| `publisher` | Editorial |
| `año` | Año de publicación (entero) |
| `published_at` | Fecha exacta de publicación |
| `summary` | Resumen o sinopsis |
| `book_cover` | Portada (imagen JPG/PNG/WebP, máx. 2 MB) |
| `path_pdf` | Ruta o URL a copia digital, si aplica (opcional) |
| `stock_total` | Total de ejemplares físicos |
| `available_copies` | Copias disponibles para préstamo |

### Gestión de stock

- Solo se puede registrar un préstamo si `available_copies > 0`
- Al registrar un préstamo, `available_copies` se decrementa en 1
- Al marcar un préstamo como devuelto, `available_copies` se incrementa en 1
- `available_copies` no puede superar `stock_total`

### Gestión de usuarios

- **Activar / Desactivar** cuentas desde el panel admin
- Los usuarios inactivos son desconectados automáticamente al navegar
- **Cambiar contraseña** desde la pantalla de edición del usuario

### Dashboard del administrador

- **KPIs**: total libros (con alerta de agotados), préstamos activos (con alerta de vencidos), usuarios (con alerta de inactivos)
- **Préstamos recientes**: últimos 5
- **Auditoría reciente**: últimos 8 registros (cambios en libros + intentos de login fallidos)

### Auditoría

| Evento | Trigger |
|---|---|
| `created` | Libro creado (`BookObserver`) |
| `updated` | Libro editado (`BookObserver`) |
| `deleted` | Libro eliminado (`BookObserver`) |
| `login_failed` | Intento de login fallido (`AuthController`) — registra email e IP |

---

## Archivos subidos (Storage)

Ejecutar `php artisan storage:link` si las imágenes no se ven en el navegador.

### Portadas de libros

| Propiedad | Valor |
|---|---|
| Formatos | jpg, jpeg, png, webp |
| Peso máximo | 2 MB |
| Dimensiones mínimas | 300 × 400 px |
| Dimensiones máximas | 400 × 500 px |
| Carpeta | `storage/app/public/books/` |

### Fotografías de autores

| Propiedad | Valor |
|---|---|
| Formatos | jpg, jpeg, png |
| Peso máximo | 2 MB |
| Carpeta | `storage/app/public/authors/` |

- Al actualizar la foto de un autor, la foto anterior se elimina del disco automáticamente
- Al eliminar un autor, su foto se elimina del disco automáticamente
