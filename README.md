# Biblioteca de Alejandría

Sistema de gestión de biblioteca desarrollado como proyecto universitario para la materia **Programación Aplicada 1** — Universidad UPED 2026.

---

## Tecnologías utilizadas

| Tecnología | Versión | Descripción |
|---|---|---|
| **PHP** | ^8.2 | Lenguaje de programación del backend |
| **Laravel** | 12 | Framework principal del proyecto (MVC, ORM, rutas, middleware) |
| **Blade** | — | Motor de plantillas de Laravel para las vistas |
| **SQLite** | — | Base de datos por defecto para desarrollo y pruebas |
| **MySQL** | — | Base de datos opcional para producción (configurable en `.env`) |
| **Tailwind CSS** | v4 | Framework de estilos utilitarios para el frontend |
| **Vite** | 7 | Herramienta de bundling y compilación de activos frontend |
| **PHPUnit** | ^11.5 | Framework de pruebas automatizadas |
| **Laravel Pint** | — | Herramienta de formato y estilo de código PHP |

---

## Estructura del proyecto

```
BibliotecaDeAlejandria/
│
├── app/                        # Código principal de la aplicación
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controladores del panel de administrador
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── BookController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── AuthorController.php
│   │   │   │   └── UserController.php
│   │   │   ├── User/           # Controladores del área de usuario
│   │   │   │   ├── CatalogoController.php
│   │   │   │   └── ProfileController.php
│   │   │   └── AuthController.php   # Login y logout
│   │   └── Middleware/
│   │       └── CheckRole.php   # Middleware de verificación de rol (admin|user)
│   └── Models/                 # Modelos Eloquent
│       ├── User.php
│       ├── Book.php
│       ├── Category.php
│       ├── Author.php
│       └── Loan.php
│
├── bootstrap/
│   └── app.php                 # Configuración de la aplicación y registro de middleware
│
├── database/
│   ├── migrations/             # Migraciones de base de datos
│   └── seeders/
│       └── DatabaseSeeder.php  # Datos iniciales (usuarios de prueba)
│
├── public/
│   └── index.php               # Punto de entrada de la aplicación web
│
├── resources/
│   ├── views/                  # Plantillas Blade
│   │   ├── layouts/            # Layouts base (admin.blade.php, app.blade.php)
│   │   ├── auth/               # Vista de login
│   │   ├── admin/              # Vistas del panel administrador
│   │   └── user/               # Vistas del área de usuario (catálogo, detalle, perfil)
│   ├── css/
│   │   └── app.css             # Estilos con Tailwind CSS
│   └── js/
│       └── app.js              # JavaScript principal
│
├── routes/
│   └── web.php                 # Definición de todas las rutas de la aplicación
│
├── storage/
│   └── app/public/books/       # Imágenes de portada de libros subidas
│
├── tests/
│   ├── Feature/                # Pruebas de integración
│   └── Unit/                   # Pruebas unitarias
│
├── vite.config.js              # Configuración de Vite
└── composer.json               # Dependencias PHP
```

---

## Instalación y puesta en marcha

```bash
# 1. Instalar dependencias, generar clave y migrar la base de datos
composer setup

# 2. Iniciar el entorno de desarrollo completo (servidor + vite + logs + cola)
composer dev
```


###########################################################################
##          >>NOTA: Programas necesarios para el funcionamiento:<<  
###########################################################################
#   Lista de programas:
-VSCODE
-COMPOSER
-LARAVEL 12
-GIT

#     Terminal 1:
npm run build
#     Terminal 2:
php artisan serve 


### Usuarios de prueba (seeder)

| Email | Contraseña | Rol |
|---|---|---|
| admin@biblioteca.com | password | a123 |
| juan@biblioteca.com | password | juan123 |




Acceder en el navegador a: `http://localhost:8000`


```
