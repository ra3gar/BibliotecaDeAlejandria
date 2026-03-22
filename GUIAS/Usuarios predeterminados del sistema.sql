-- ====================================================================
--  INSERCIÓN MANUAL DE USUARIOS PREDETERMINADOS
--  Biblioteca de Alejandría — Laravel 12
--  Universidad UPED 2026 — Programación Aplicada 1
--  Actualizado: 2026-03-22
-- ====================================================================
--
--  ⚠️  USA ESTE ARCHIVO SOLO COMO ÚLTIMO RECURSO
--
--  La forma CORRECTA y segura de crear estos usuarios es con el seeder:
--
--      php artisan migrate:fresh --seed
--
--  Ese comando genera contraseñas con Hash::make() de Laravel,
--  que usa el factor de costo configurado en config/hashing.php
--  (por defecto bcrypt cost=12, que es lo recomendado en producción).
--
--  ¿Cuándo usar ESTE archivo?
--    • Cuando no tienes acceso a la terminal del servidor Laravel
--    • Cuando accedes directamente desde MySQL Workbench o phpMyAdmin
--    • Para recuperar acceso de emergencia al admin en un servidor remoto
--
--  ⚠️  ADVERTENCIAS DE SEGURIDAD:
--    • Los hashes de este archivo son válidos SOLO en desarrollo
--    • Corresponden a la contraseña "password" con bcrypt(cost=12)
--    • NUNCA uses la contraseña "password" en producción
--    • En producción, cambia la contraseña inmediatamente después de
--      crear el usuario con: /admin/users/{id}/edit → Cambiar contraseña
--
-- ====================================================================

USE biblioteca_alejandria;


-- --------------------------------------------------------------------
-- USUARIO ADMINISTRADOR DEL SISTEMA
--
-- Tiene acceso completo al panel de administración en /admin/dashboard:
--   • Gestión de libros, autores, categorías
--   • Gestión de préstamos (confirmar entrega, marcar devolución)
--   • Gestión de usuarios (crear, editar, activar/desactivar)
--   • Visualización del log de auditoría
--
-- Credenciales de desarrollo: admin@biblioteca.com / password
-- --------------------------------------------------------------------
INSERT INTO book_store_users
    (first_name, last_name, email, password, role, is_active, birth_date, created_at, updated_at)
VALUES
    (
        'Admin',
        'Sistema',
        'admin@biblioteca.com',
        '$2y$12$Nk/9YEf8GzKgHnjB2VWv9uVCXwy9HI9pGYbCf66aKX.FccAxHTlMu',  -- bcrypt(cost=12): "password"
        'admin',
        1,        -- is_active = 1 (activo)
        NULL,     -- birth_date NULL: no necesita pasar validación de edad para préstamos
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    updated_at = updated_at;  -- No sobreescribe si ya existe (operación idempotente)


-- --------------------------------------------------------------------
-- USUARIO ESTÁNDAR DE PRUEBA
--
-- Tiene acceso al catálogo de libros (/catalogo) y a su perfil (/perfil).
-- Puede reservar libros y ver el estado de sus préstamos con QR.
--
-- NOTA: birth_date es NULL. Esto significa que la validación de edad
-- mínima en préstamos se OMITE automáticamente para este usuario.
-- Si quieres probar la restricción de edad, usa el tercer usuario de
-- prueba definido más abajo, o crea uno con birth_date real.
--
-- Credenciales de desarrollo: user@biblioteca.com / password
-- --------------------------------------------------------------------
INSERT INTO book_store_users
    (first_name, last_name, email, password, role, is_active, birth_date, created_at, updated_at)
VALUES
    (
        'User',
        'Sistema',
        'user@biblioteca.com',
        '$2y$12$wKtYKt0DuVjNgEG88lqp1u/f5tHx3a1y.ZKP6Zpbqwnz0inx/R83y',  -- bcrypt(cost=12): "password"
        'user',
        1,        -- is_active = 1 (activo)
        NULL,     -- birth_date NULL: se omite la validación de edad en préstamos
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    updated_at = updated_at;


-- --------------------------------------------------------------------
-- USUARIO DE PRUEBA CON FECHA DE NACIMIENTO (para probar min_age)
--
-- Este usuario tiene 20 años, lo que permite verificar el flujo
-- completo de restricciones de edad en préstamos:
--
--   • Puede reservar libros con min_age <= 20
--   • NO puede reservar libros con min_age > 20 (ej: 21, 25, 18+)
--
-- Para probar el rechazo por edad:
--   1. Crea un libro con min_age = 21
--   2. Inicia sesión como adulto@biblioteca.com
--   3. Intenta reservar ese libro → debe mostrar error de edad
--
-- Credenciales de desarrollo: adulto@biblioteca.com / password
-- --------------------------------------------------------------------
INSERT INTO book_store_users
    (first_name, last_name, email, password, role, is_active, birth_date, created_at, updated_at)
VALUES
    (
        'Usuario',
        'Adulto',
        'adulto@biblioteca.com',
        '$2y$12$wKtYKt0DuVjNgEG88lqp1u/f5tHx3a1y.ZKP6Zpbqwnz0inx/R83y',  -- bcrypt(cost=12): "password"
        'user',
        1,
        DATE_SUB(NOW(), INTERVAL 20 YEAR),  -- Fecha de nacimiento dinámica: exactamente 20 años atrás
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    birth_date = DATE_SUB(NOW(), INTERVAL 20 YEAR),  -- Actualiza edad si ya existe
    updated_at = NOW();


-- ====================================================================
--  VERIFICACIÓN — Ejecuta estas queries para confirmar la inserción
-- ====================================================================

SELECT
    id,
    CONCAT(first_name, ' ', last_name)  AS nombre_completo,
    email,
    role,
    is_active                           AS activo,
    birth_date,
    TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS edad_calculada
FROM book_store_users
WHERE email IN (
    'admin@biblioteca.com',
    'user@biblioteca.com',
    'adulto@biblioteca.com'
)
ORDER BY role DESC, email;

-- ====================================================================
--  RESUMEN DE ACCESOS
-- ====================================================================
--
--  Email                    | Contraseña | Rol   | Panel de acceso
--  ─────────────────────────┼────────────┼───────┼──────────────────────
--  admin@biblioteca.com     | password   | admin | /admin/dashboard
--  user@biblioteca.com      | password   | user  | /catalogo (sin birth_date)
--  adulto@biblioteca.com    | password   | user  | /catalogo (con 20 años de edad)
--
--  Para probar restricción de edad:
--    1. Crea un libro con min_age = 21 en el panel admin
--    2. Inicia sesión como adulto@biblioteca.com (20 años)
--    3. Intenta reservar el libro → debe rechazarse con error de edad mínima
--
-- ====================================================================
