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
| **Alpine.js** | 3.x | Reactividad en el frontend (dropdowns, carrusel, formularios) |
| **Vite** | 7 | Herramienta de bundling y compilación de activos frontend |
| **PHPUnit** | ^11.5 | Framework de pruebas automatizadas |
| **Laravel Pint** | — | Herramienta de formato y estilo de código PHP |

---

## Requisitos del sistema

| Herramienta | Versión mínima | Notas |
|---|---|---|
| **PHP** | 8.2 | Con extensiones: pdo_mysql, mbstring, openssl, tokenizer, xml |
| **Composer** | 2.x | Gestor de dependencias de PHP |
| **Node.js** | 18.x | Para compilar assets con Vite y Tailwind |
| **npm** | 9.x | Incluido con Node.js |
| **MySQL** | 8.0 | Servidor de base de datos local |
| **Git** | — | Control de versiones |

---

## Instalación — Paso a paso

### Paso 1 — Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd BibliotecaDeAlejandria
```

### Paso 2 — Configurar la base de datos ⚠️ Hacer ANTES de instalar

Crea la base de datos en MySQL:

```sql
CREATE DATABASE biblioteca_alejandria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Luego copia el archivo de entorno y configura tus credenciales:

```bash
cp .env.example .env
```

Edita `.env` con tu editor y ajusta:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_alejandria
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

### Paso 3 — Instalar dependencias y migrar

```bash
composer setup
```

Este comando hace todo de una vez:
- Instala dependencias PHP (`composer install`)
- Genera la clave de aplicación (`php artisan key:generate`)
- Ejecuta todas las migraciones (`php artisan migrate`)
- Instala dependencias Node.js (`npm install`)
- Compila los assets frontend (`npm run build`)

### Paso 4 — Enlazar el almacenamiento público

```bash
php artisan storage:link
```

> **Obligatorio** para que se muestren las portadas de libros y fotos de autores.

### Paso 5 — Sembrar datos de prueba (opcional)

```bash
php artisan db:seed
```

Crea los usuarios de prueba iniciales (ver tabla más abajo).

### Paso 6 — Iniciar el entorno de desarrollo

```bash
composer dev
```

Acceder en el navegador a: `http://localhost:8000`

---

## Flujo de desarrollo correcto

```
Terminal 1 (única terminal necesaria):

  composer dev
    ├── php artisan serve     → servidor PHP en localhost:8000
    ├── npm run dev           → Vite con hot reload en localhost:5173
    └── php artisan queue:listen → procesador de colas

Para cerrar: Ctrl+C — detiene los 3 procesos a la vez.
```

> ⚠️ **No ejecutes** `php artisan serve` en una terminal separada mientras `composer dev` está activo — ya lo incluye internamente y causará conflicto de puertos.

> ⚠️ Si los estilos no cargan, verifica que no exista el archivo `public/hot`:
> ```bash
> rm -f public/hot
> ```

---

## Comandos de referencia

```bash
# Arrancar el entorno completo (servidor + queue + vite)
composer dev

# Solo compilar assets (para producción o si no usas hot reload)
npm run build

# Ejecutar pruebas automatizadas
composer test

# Migrar y sembrar datos de prueba
php artisan migrate --seed

# Reiniciar base de datos desde cero
php artisan migrate:fresh --seed

# Limpiar caché de configuración y vistas
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Verificar enlace de almacenamiento público
php artisan storage:link

# Abrir consola interactiva de MySQL (verifica conexión)
php artisan db

# Formatear código según estándar Laravel
vendor/bin/pint
```

---

## Usuarios de prueba

| Email | Contraseña | Rol | Acceso |
|---|---|---|---|
| `admin@biblioteca.com` | `password` | admin | Panel de administración (`/admin/dashboard`) |
| `juan@biblioteca.com` | `password` | user | Catálogo público (`/catalogo`) |

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
│   │   │   │   ├── BookController.php        # CRUD libros + stock físico
│   │   │   │   ├── CategoryController.php    # CRUD categorías
│   │   │   │   ├── AuthorController.php      # CRUD autores + gestión de fotos
│   │   │   │   ├── LoanController.php        # Registro, devolución y control de stock
│   │   │   │   └── UserController.php        # CRUD + activar/desactivar + cambiar contraseña
│   │   │   ├── User/
│   │   │   │   ├── CatalogoController.php    # Catálogo público, filtros por categoría y autor
│   │   │   │   └── ProfileController.php     # Perfil del usuario
│   │   │   └── AuthController.php            # Login, logout + auditoría de fallos
│   │   └── Middleware/
│   │       └── CheckRole.php                 # Verifica rol y estado activo del usuario
│   ├── Models/
│   │   ├── User.php          # is_active, isAdmin(), isActive(), full_name
│   │   ├── Book.php          # stock_total, available_copies, año (derivado de published_at)
│   │   ├── Category.php
│   │   ├── Author.php        # photo_path, photo_url accessor, bio
│   │   ├── Loan.php          # Relaciones user/book, estados de préstamo
│   │   └── AuditLog.php      # Registro de auditoría
│   ├── Observers/
│   │   └── BookObserver.php  # Graba en audit_logs al crear/editar/eliminar libros
│   └── Providers/
│       └── AppServiceProvider.php  # Registra BookObserver
│
├── database/
│   └── migrations/           # 13 migraciones en orden cronológico
│
├── public/
│   ├── images/
│   │   ├── logo.png          # Logo del sistema (header y login)
│   │   ├── Fondo.png         # Imagen de fondo — pantalla de login
│   │   └── Fondo2.jpg        # Imagen de fondo — panel admin y catálogo
│   └── build/                # Assets compilados por Vite (generado automáticamente)
│
├── resources/
│   ├── css/app.css           # Estilos globales + tokens de diseño Tailwind v4
│   ├── js/app.js
│   └── views/
│       ├── layouts/
│       │   ├── admin.blade.php   # Layout del panel de administración
│       │   └── app.blade.php     # Layout del portal de usuario
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── books/            # index, create, edit, _form
│       │   ├── loans/            # index, show, create
│       │   ├── users/            # index, create, edit
│       │   ├── categories/       # index, create, edit
│       │   └── authors/          # index, create, edit
│       └── user/
│           ├── catalogo.blade.php
│           ├── catalogo-filtrado.blade.php
│           ├── book-detail.blade.php
│           └── profile.blade.php
│
├── storage/
│   └── app/public/
│       ├── books/            # Portadas de libros subidas
│       └── authors/          # Fotos de autores subidas
│
├── GUIAS/
│   ├── ConexionaBD.txt       # Guía de configuración de base de datos
│   └── COMANDOS PARA MYSQL PARA LA CREACION DE LA BD.sql  # Esquema SQL de referencia
│
├── routes/web.php
└── composer.json
```

---

## Módulos implementados

### Gestión de libros

| Campo | Descripción |
|---|---|
| `title` | Título del libro (requerido) |
| `isbn` | ISBN (único, opcional) |
| `publisher` | Editorial |
| `published_at` | Fecha exacta de publicación |
| `año` | Año — se deriva automáticamente de `published_at` al guardar |
| `summary` | Resumen o sinopsis |
| `book_cover` | Portada (JPG/PNG/WebP, máx. 2 MB, 300×400 — 400×500 px) |
| `stock_total` | Total de ejemplares físicos en la biblioteca |
| `available_copies` | Copias actualmente disponibles para préstamo |

### Gestión de stock

- Solo se puede registrar un préstamo si `available_copies > 0`
- Al registrar un préstamo, `available_copies` se decrementa en 1
- Al marcar un préstamo como devuelto, `available_copies` se incrementa en 1
- `available_copies` no puede superar `stock_total`
- Protección contra doble devolución del mismo préstamo

### Gestión de usuarios

- **Activar / Desactivar** cuentas desde el panel admin
- Los usuarios inactivos son desconectados automáticamente al navegar
- **Cambiar contraseña** desde la pantalla de edición del usuario

### Dashboard del administrador

- **KPIs**: total libros (con alerta de agotados), préstamos activos (con alerta de vencidos), usuarios (con alerta de inactivos)
- **Préstamos recientes**: últimos 5 registros con estado
- **Auditoría reciente**: últimos 8 registros de cualquier tipo

### Auditoría

| Evento | Cuándo se registra |
|---|---|
| `created` | Al crear un libro (`BookObserver`) |
| `updated` | Al editar un libro (`BookObserver`) |
| `deleted` | Al eliminar un libro (`BookObserver`) |
| `login_failed` | Intento de login fallido — registra email e IP (`AuthController`) |

---

## Archivos subidos (Storage)

> Ejecutar `php artisan storage:link` si las imágenes no se ven en el navegador.

### Portadas de libros

| Propiedad | Valor |
|---|---|
| Formatos aceptados | jpg, jpeg, png, webp |
| Peso máximo | 2 MB |
| Dimensiones mínimas | 300 × 400 px |
| Dimensiones máximas | 400 × 500 px |
| Carpeta de almacenamiento | `storage/app/public/books/` |

### Fotografías de autores

| Propiedad | Valor |
|---|---|
| Formatos aceptados | jpg, jpeg, png |
| Peso máximo | 2 MB |
| Carpeta de almacenamiento | `storage/app/public/authors/` |

- Al actualizar la foto de un autor, la foto anterior se elimina del disco automáticamente
- Al eliminar un autor, su foto se elimina del disco automáticamente

### Imágenes estáticas del sitio

Ubicadas en `public/images/` (no requieren `storage:link`):

| Archivo | Uso |
|---|---|
| `logo.png` | Logo del sistema en header y pantalla de login |
| `Fondo.png` | Imagen de fondo de la pantalla de login |
| `Fondo2.jpg` | Imagen de fondo del panel de administración y catálogo |
