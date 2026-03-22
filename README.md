# Biblioteca de Alejandría

Sistema de gestión de biblioteca física con **préstamos híbridos** (reserva online + verificación presencial por código QR), desarrollado como proyecto universitario para la materia **Programación Aplicada 1** — Universidad UPED 2026.

El proyecto digitaliza el flujo completo de préstamo de una biblioteca real:
un usuario reserva desde el catálogo web, se genera un QR único, lo presenta en mostrador y el administrador confirma la entrega y la devolución. Todo queda auditado.

---

## Tecnologías utilizadas

| Tecnología | Versión | Descripción |
|---|---|---|
| **PHP** | ^8.2 | Lenguaje de programación del backend |
| **Laravel** | 12 | Framework principal del proyecto (MVC, ORM, rutas, middleware) |
| **Blade** | — | Motor de plantillas de Laravel para las vistas |
| **MySQL** | 8.0 | Base de datos para desarrollo local (configurable en `.env`) |
| **SQLite** | — | Base de datos en memoria para pruebas automatizadas |
| **Tailwind CSS** | v4 | Framework de estilos utilitarios para el frontend |
| **Alpine.js** | 3.x | Reactividad en el frontend (tabs, dropdowns, formularios) |
| **Vite** | 7 | Bundling y compilación de assets frontend (CSS/JS) |
| **PHPUnit** | ^11.5 | Framework de pruebas automatizadas |
| **Laravel Pint** | — | Formateador de código PHP según estándar Laravel |
| **simple-qrcode** | ^4.2 | Generación de códigos QR en SVG inline (`simplesoftwareio/simple-qrcode`) |

---

## Requisitos del sistema

| Herramienta | Versión mínima | Notas |
|---|---|---|
| **PHP** | 8.2 | Con extensiones: pdo_mysql, mbstring, openssl, tokenizer, xml |
| **Composer** | 2.x | Gestor de dependencias PHP |
| **Node.js** | 18.x | Para compilar assets con Vite y Tailwind |
| **npm** | 9.x | Incluido con Node.js |
| **MySQL** | 8.0 | Servidor de base de datos local |
| **Git** | — | Control de versiones |

---

## Instalación — Paso a paso

> Para una guía más detallada con troubleshooting y opciones avanzadas,
> consulta `GUIAS/ConexionaBD.txt`.

### Paso 1 — Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd BibliotecaDeAlejandria
```

### Paso 2 — Crear la base de datos ⚠️ Hacer ANTES de instalar

Crea la base de datos en MySQL:

```sql
CREATE DATABASE biblioteca_alejandria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Luego copia el archivo de entorno:

```bash
cp .env.example .env
```

Edita `.env` con tus credenciales:

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
- Instala dependencias PHP (`composer install`) incluyendo `simplesoftwareio/simple-qrcode`
- Genera la clave de aplicación (`php artisan key:generate`)
- Ejecuta las 17 migraciones (`php artisan migrate`)
- Instala dependencias Node.js (`npm install`)
- Compila los assets frontend (`npm run build`)

### Paso 4 — Enlazar el almacenamiento público

```bash
php artisan storage:link
```

> **Obligatorio** para que se muestren las portadas de libros y fotos de autores en el navegador.

### Paso 5 — Sembrar datos de prueba (opcional)

```bash
php artisan db:seed
```

Crea los usuarios de prueba iniciales (ver tabla más abajo).

### Paso 6 — Iniciar el entorno de desarrollo

```bash
composer dev
```

Acceder en el navegador: `http://localhost:8000`

---

## Flujo de desarrollo correcto

```
Terminal 1 (única terminal necesaria):

  composer dev
    ├── php artisan serve        → servidor PHP en localhost:8000
    ├── npm run dev              → Vite con hot reload en localhost:5173
    └── php artisan queue:listen → procesador de colas en background

Para cerrar: Ctrl+C — detiene los 3 procesos a la vez.
```

> ⚠️ **No ejecutes** `php artisan serve` en una terminal separada mientras `composer dev` está activo — ya lo incluye y causará conflicto de puertos.

> ⚠️ Si los estilos no cargan, verifica que no exista el archivo residual de Vite:
> ```bash
> rm -f public/hot
> ```

---

## Comandos de referencia

```bash
# Arrancar el entorno completo (servidor + queue + vite)
composer dev

# Solo compilar assets (para producción o sin hot reload)
npm run build

# Ejecutar todas las pruebas automatizadas
composer test

# Ejecutar un archivo de prueba específico
php artisan test tests/Feature/ExampleTest.php

# Ejecutar una prueba por nombre
php artisan test --filter=nombre_del_test

# Migrar y sembrar datos de prueba
php artisan migrate --seed

# Reiniciar base de datos desde cero (¡elimina todos los datos!)
php artisan migrate:fresh --seed

# Formatear código según estándar Laravel (solo archivos modificados)
vendor/bin/pint --dirty

# Limpiar cachés de Laravel
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Verificar enlace de almacenamiento público
php artisan storage:link

# Abrir consola interactiva de MySQL (verifica la conexión)
php artisan db
```

---

## Usuarios de prueba

| Email | Contraseña | Rol | Panel de acceso | Notas |
|---|---|---|---|---|
| `admin@biblioteca.com` | `password` | admin | `/admin/dashboard` | Acceso total al sistema |
| `user@biblioteca.com` | `password` | user | `/catalogo` | Sin `birth_date` — la validación de edad se omite |
| `adulto@biblioteca.com` | `password` | user | `/catalogo` | Con 20 años calculados — para probar `min_age` |

> El tercer usuario se inserta solo con el SQL manual en `GUIAS/Usuarios predeterminados del sistema.sql`,
> no con el seeder estándar. Útil para probar el rechazo por edad mínima en préstamos.

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
│   │   │   │   ├── BookController.php        # CRUD libros + stock físico + min_age
│   │   │   │   ├── CategoryController.php    # CRUD categorías
│   │   │   │   ├── AuthorController.php      # CRUD autores + gestión de fotos
│   │   │   │   ├── LoanController.php        # Registro, QR, confirmPickup, devolución
│   │   │   │   └── UserController.php        # CRUD + activar/desactivar + cambiar contraseña
│   │   │   ├── User/
│   │   │   │   ├── CatalogoController.php    # Catálogo público, filtros por categoría y autor
│   │   │   │   ├── LoanController.php        # Auto-reserva de libros por el usuario
│   │   │   │   └── ProfileController.php     # Perfil del usuario con historial de préstamos
│   │   │   └── AuthController.php            # Login, logout + auditoría de fallos
│   │   └── Middleware/
│   │       └── CheckRole.php                 # Verifica rol y estado activo (bloquea inactivos)
│   ├── Models/
│   │   ├── User.php          # is_active, birth_date, isAdmin(), isActive(), age(), full_name
│   │   ├── Book.php          # stock_total, available_copies, min_age, isAvailable()
│   │   ├── Category.php
│   │   ├── Author.php        # photo_path, photo_url accessor, bio
│   │   ├── Loan.php          # status (pending/active/returned/overdue), qr_token, scopes
│   │   └── AuditLog.php      # Registro de auditoría
│   ├── Observers/
│   │   └── BookObserver.php  # Graba en audit_logs al crear/editar/eliminar libros
│   └── Providers/
│       └── AppServiceProvider.php  # Registra BookObserver
│
├── database/
│   └── migrations/           # 17 migraciones en orden cronológico
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
│       │   ├── loans/            # index, show, create (con QR en show)
│       │   ├── users/            # index, create, edit
│       │   ├── categories/       # index, create, edit
│       │   └── authors/          # index, create, edit
│       └── user/
│           ├── catalogo.blade.php
│           ├── catalogo-filtrado.blade.php
│           ├── book-detail.blade.php   # Con botón "Reservar este libro"
│           └── profile.blade.php       # Con QR colapsable para préstamos pendientes
│
├── storage/
│   └── app/public/
│       ├── books/            # Portadas de libros subidas
│       └── authors/          # Fotos de autores subidas
│
├── GUIAS/
│   ├── ConexionaBD.txt                              # Guía de configuración de BD
│   ├── COMANDOS PARA MYSQL PARA LA CREACION DE LA BD.sql  # Esquema SQL de referencia
│   └── Usuarios predeterminados del sistema.sql    # Inserción manual de usuarios de prueba
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
| `min_age` | Edad mínima requerida para solicitar el libro (0 = sin restricción) |

### Gestión de stock

- Solo se puede registrar un préstamo si `available_copies > 0`
- El stock (`available_copies`) se decrementa al crear la **reserva** (`pending`)
- El stock se incrementa al **marcar como devuelto** (`returned`)
- `available_copies` no puede superar `stock_total`
- Protección contra doble devolución del mismo préstamo

### Sistema de préstamos híbrido con QR

El flujo de préstamo combina una reserva web con entrega presencial verificada por código QR:

1. **Reserva online** — el usuario hace clic en "Reservar este libro" desde el catálogo. Se crea el préstamo en estado `pending` y se genera un `qr_token` UUID único. El stock se decrementa en este momento.
2. **QR en el perfil** — el usuario ve sus reservas pendientes en `/perfil`. Puede expandir cada una para ver su código QR.
3. **Entrega física** — el usuario se presenta en la biblioteca con el QR en pantalla. El admin escanea el QR o busca el préstamo manualmente y hace clic en "Confirmar entrega". El préstamo pasa a estado `active`.
4. **Devolución** — cuando el usuario regresa el libro físico, el admin hace clic en "Marcar como devuelto". El préstamo pasa a `returned` y el stock se incrementa.

### Restricción de edad en préstamos

- Cada libro puede tener un campo `min_age` (entero, default 0)
- Si `min_age > 0`, el sistema verifica que el usuario tenga al menos esa edad calculada desde su `birth_date`
- Si el usuario no tiene `birth_date` registrado, la validación se omite (no bloquea el préstamo)
- La verificación aplica tanto en reservas del usuario como en préstamos creados manualmente por el admin

### Gestión de usuarios

- **Activar / Desactivar** cuentas desde el panel admin
- Los usuarios inactivos son desconectados automáticamente al navegar (middleware `CheckRole`)
- **Cambiar contraseña** desde la pantalla de edición del usuario
- Campo `birth_date` para registrar la fecha de nacimiento (usado en restricciones de edad)

### Dashboard del administrador

- **KPIs**: total libros (con alerta de agotados), préstamos activos (con alerta de vencidos), usuarios (con alerta de inactivos)
- **Préstamos recientes**: últimos 5 registros con estado visual por color
- **Auditoría reciente**: últimos 8 registros de cualquier tipo

### Auditoría del sistema

| Evento | Disparador | Descripción |
|---|---|---|
| `created` | `BookObserver` | Al crear un libro |
| `updated` | `BookObserver` | Al editar un libro |
| `deleted` | `BookObserver` | Al eliminar un libro |
| `updated` | `LoanController` | Al confirmar entrega de un préstamo (`pending → active`) |
| `updated` | `LoanController` | Al registrar devolución de un préstamo (`→ returned`) |
| `login_failed` | `AuthController` | Intento de login fallido — registra email e IP |

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

- Al actualizar la foto, la anterior se elimina del disco automáticamente
- Al eliminar un autor, su foto se elimina del disco automáticamente

### Imágenes estáticas del sitio

Ubicadas en `public/images/` (no requieren `storage:link`):

| Archivo | Uso |
|---|---|
| `logo.png` | Logo del sistema en header y pantalla de login |
| `Fondo.png` | Imagen de fondo de la pantalla de login |
| `Fondo2.jpg` | Imagen de fondo del panel de administración y catálogo |
