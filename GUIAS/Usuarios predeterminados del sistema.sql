-- ====================================================================
--  INSERCIÓN MANUAL DE USUARIOS PREDETERMINADOS
--  Proyecto: BibliotecaDeAlejandria — Laravel 12
--  Universidad UPED 2026 — Programación Aplicada 1
-- ====================================================================
--
--  ⚠️  USA ESTO SOLO SI NO PUEDES EJECUTAR php artisan db:seed
--
--  La forma CORRECTA de crear estos usuarios es con el seeder:
--
--      php artisan migrate:fresh --seed
--
--  Ese comando genera contraseñas con Hash::make() de Laravel,
--  que usa el factor de costo correcto configurado en la app.
--
--  Los hashes incluidos aquí fueron generados con bcrypt (cost=12)
--  para la contraseña "password" — son válidos para inserción manual
--  únicamente en entorno de DESARROLLO.
--
--  NUNCA uses estas contraseñas en producción.
-- ====================================================================

USE biblioteca_alejandria;

-- --------------------------------------------------------------------
-- Usuario administrador del sistema
-- Credenciales: admin@biblioteca.com / password
-- Acceso: panel completo en /admin/dashboard
-- --------------------------------------------------------------------
INSERT INTO book_store_users
    (first_name, last_name, email, password, role, is_active, birth_date, created_at, updated_at)
VALUES
    (
        'Admin',
        'Sistema',
        'admin@biblioteca.com',
        '$2y$12$Nk/9YEf8GzKgHnjB2VWv9uVCXwy9HI9pGYbCf66aKX.FccAxHTlMu',
        'admin',
        1,
        NULL,
        NOW(),
        NOW()
    );

-- --------------------------------------------------------------------
-- Usuario estándar de prueba
-- Credenciales: user@biblioteca.com / password
-- Acceso: catálogo de libros (/catalogo) y perfil (/perfil)
-- Nota: birth_date es NULL — la validación de edad en préstamos
--       se omite automáticamente cuando este campo no está registrado
-- --------------------------------------------------------------------
INSERT INTO book_store_users
    (first_name, last_name, email, password, role, is_active, birth_date, created_at, updated_at)
VALUES
    (
        'User',
        'Sistema',
        'user@biblioteca.com',
        '$2y$12$wKtYKt0DuVjNgEG88lqp1u/f5tHx3a1y.ZKP6Zpbqwnz0inx/R83y',
        'user',
        1,
        NULL,
        NOW(),
        NOW()
    );

-- ====================================================================
--  RESUMEN DE ACCESOS
-- ====================================================================
--
--  Email                   | Contraseña | Rol   | Panel
--  ────────────────────────┼────────────┼───────┼──────────────────────
--  admin@biblioteca.com    | password   | admin | /admin/dashboard
--  user@biblioteca.com     | password   | user  | /catalogo
--
-- ====================================================================
