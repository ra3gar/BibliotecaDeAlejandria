-- ====================================================================
--  REFERENCIA SQL — Biblioteca de Alejandría
--  Universidad UPED 2026 — Programación Aplicada 1
--  Actualizado: 2026-03-21
-- ====================================================================
--
--  ⚠️  IMPORTANTE: Este archivo es SOLO de referencia visual.
--      NO ejecutes estos CREATE TABLE manualmente en producción.
--      Las tablas las crea Laravel automáticamente con:
--
--          php artisan migrate
--      o (borra todo y recrea con datos de prueba):
--          php artisan migrate:fresh --seed
--
--      Este script refleja el estado FINAL tras las 17 migraciones
--      disponibles en database/migrations/.
-- ====================================================================


-- --------------------------------------------------------------------
-- 0. Crear y seleccionar la base de datos
--
--    utf8mb4 es el juego de caracteres correcto para español:
--    soporta tildes (á, é, ñ), comillas tipográficas y emojis.
--    utf8mb4_unicode_ci ordena y compara sin distinguir mayúsculas.
-- --------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS biblioteca_alejandria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca_alejandria;


-- --------------------------------------------------------------------
-- 1. Usuarios del sistema (book_store_users)
--
--    Usamos un nombre de tabla personalizado (en lugar del estándar
--    "users") para evitar colisiones si se reutiliza la BD en otro
--    proyecto Laravel que también cree una tabla "users".
--
--    Campos importantes:
--      • role       → 'admin' gestiona todo; 'user' solo consulta
--      • is_active  → si vale 0, el middleware CheckRole desconecta
--                     al usuario automáticamente al navegar
--      • birth_date → fecha de nacimiento, opcional; se usa para
--                     validar la edad mínima requerida por ciertos
--                     libros (campo min_age en books). Si es NULL,
--                     la validación de edad se omite silenciosamente.
-- --------------------------------------------------------------------
CREATE TABLE book_store_users (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name     VARCHAR(255)          NOT NULL,
    last_name      VARCHAR(255)          NOT NULL,
    email          VARCHAR(255)          NOT NULL UNIQUE,
    password       VARCHAR(255)          NOT NULL,           -- Hash bcrypt, NUNCA texto plano
    role           ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    is_active      TINYINT(1)            NOT NULL DEFAULT 1, -- 1 = activo, 0 = bloqueado
    birth_date     DATE                  NULL,               -- Para restricción de edad en préstamos
    remember_token VARCHAR(100)          NULL,
    created_at     TIMESTAMP             NULL,
    updated_at     TIMESTAMP             NULL
);

-- ── Tablas auxiliares generadas por las migraciones base de Laravel ──
-- (No es necesario crearlas manualmente; se incluyen solo por completitud)

CREATE TABLE password_reset_tokens (
    email      VARCHAR(255) NOT NULL PRIMARY KEY,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP    NULL
);

CREATE TABLE sessions (
    id            VARCHAR(255)    NOT NULL PRIMARY KEY,
    user_id       BIGINT UNSIGNED NULL,
    ip_address    VARCHAR(45)     NULL,
    user_agent    TEXT            NULL,
    payload       LONGTEXT        NOT NULL,
    last_activity INT             NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

CREATE TABLE cache (
    cache_key  VARCHAR(255) NOT NULL PRIMARY KEY,
    value      MEDIUMTEXT   NOT NULL,
    expiration INT          NOT NULL
);

CREATE TABLE jobs (
    id           BIGINT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    queue        VARCHAR(255)     NOT NULL,
    payload      LONGTEXT         NOT NULL,
    attempts     TINYINT UNSIGNED NOT NULL,
    reserved_at  INT UNSIGNED     NULL,
    available_at INT UNSIGNED     NOT NULL,
    created_at   INT UNSIGNED     NOT NULL,
    INDEX jobs_queue_index (queue)
);


-- --------------------------------------------------------------------
-- 2. Categorías (categories)
--
--    Clasificación temática de los libros: Ciencia Ficción,
--    Historia, Derecho, etc. Un libro pertenece a una sola categoría.
--    Si se elimina la categoría, los libros quedan sin categoría
--    (category_id = NULL) gracias a ON DELETE SET NULL.
-- --------------------------------------------------------------------
CREATE TABLE categories (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT         NULL,
    created_at  TIMESTAMP    NULL,
    updated_at  TIMESTAMP    NULL
);


-- --------------------------------------------------------------------
-- 3. Autores (authors)
--
--    Un libro puede tener múltiples autores (relación muchos a muchos,
--    gestionada por la tabla pivote book_author).
--
--    • bio        → biografía larga del autor, se muestra en la ficha
--                   del libro y en el panel de administración
--    • photo_path → ruta relativa dentro de storage/app/public/authors/
--                   El accesor $author->photo_url devuelve la URL pública
--                   completa, o null si no tiene foto
--
--    Al eliminar un autor, su foto se borra del disco automáticamente
--    gracias al observer registrado en AuthorController@destroy.
-- --------------------------------------------------------------------
CREATE TABLE authors (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name  VARCHAR(255) NOT NULL,
    bio        TEXT         NULL,
    photo_path VARCHAR(255) NULL,   -- Ej: "authors/foto_perez.jpg"
    created_at TIMESTAMP    NULL,
    updated_at TIMESTAMP    NULL
);


-- --------------------------------------------------------------------
-- 4. Libros (books)
--
--    Estado final tras TODAS las migraciones (incluyendo las de
--    2026-03-21 que agregan min_age y la corrección que eliminó los
--    campos codigo_interno y path_pdf).
--
--    Gestión de stock:
--      • stock_total      → ejemplares físicos totales en biblioteca
--      • available_copies → ejemplares disponibles para préstamo ahora
--      • INVARIANTE: 0 <= available_copies <= stock_total siempre
--
--    Campos especiales:
--      • año      → se calcula automáticamente a partir de published_at
--                   en BookController; el formulario no lo envía
--      • min_age  → edad mínima en años para poder reservar este libro
--                   0 = sin restricción (cualquiera puede reservarlo)
--
--    Campos eliminados en migración 2026_03_15_000001:
--      • codigo_interno → campo heredado, nunca se usó
--      • path_pdf       → funcionalidad de préstamo digital descartada
-- --------------------------------------------------------------------
CREATE TABLE books (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id      BIGINT UNSIGNED NULL,
    title            VARCHAR(255)    NOT NULL,
    isbn             VARCHAR(20)     NULL UNIQUE,
    summary          TEXT            NULL,
    publisher        VARCHAR(150)    NULL,
    book_cover       VARCHAR(255)    NULL,   -- Ruta en storage/app/public/books/
    published_at     DATE            NULL,
    año              INT             NULL,   -- Derivado de published_at al guardar
    stock_total      INT UNSIGNED    NOT NULL DEFAULT 0,
    available_copies INT UNSIGNED    NOT NULL DEFAULT 0,
    min_age          INT UNSIGNED    NOT NULL DEFAULT 0,  -- 0 = sin restricción de edad
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);


-- --------------------------------------------------------------------
-- 5. Tabla pivote: Libros ↔ Autores (book_author)
--
--    Implementa la relación Muchos a Muchos:
--      Un libro puede tener N autores.
--      Un autor puede haber escrito N libros.
--
--    No tiene id propio ni timestamps: solo las dos claves foráneas.
--    En Laravel se gestiona con: $book->authors()->sync([id1, id2, ...])
--
--    Si se elimina un libro o autor, sus registros pivote desaparecen
--    automáticamente por el CASCADE ON DELETE.
-- --------------------------------------------------------------------
CREATE TABLE book_author (
    book_id   BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (book_id, author_id),
    CONSTRAINT fk_ba_book   FOREIGN KEY (book_id)   REFERENCES books(id)   ON DELETE CASCADE,
    CONSTRAINT fk_ba_author FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
);


-- --------------------------------------------------------------------
-- 6. Préstamos (loans)
--
--    Implementa el flujo híbrido presencial + web con código QR.
--
--    CICLO DE VIDA DE UN PRÉSTAMO:
--    ──────────────────────────────────────────────────────────────
--    [1] Usuario reserva desde el catálogo web
--          → status = 'pending'
--          → se genera un qr_token (UUID) único
--          → available_copies SE DECREMENTA en este momento
--
--    [2] Usuario se presenta físicamente en la biblioteca
--          con el QR en pantalla o impreso
--
--    [3] Admin escanea el QR (o abre el préstamo manualmente)
--        y hace clic en "Confirmar entrega"
--          → status = 'active'
--          → se registra en audit_logs
--
--    [4] Usuario devuelve el libro físico
--        Admin hace clic en "Marcar como devuelto"
--          → status = 'returned'
--          → available_copies SE INCREMENTA en este momento
--          → se registra en audit_logs
--
--    [E] Si el usuario no recoge el libro a tiempo
--          → status = 'overdue' (el admin lo marca manualmente)
--    ──────────────────────────────────────────────────────────────
--
--    qr_token:
--      • UUID generado con Str::uuid() en el momento de crear la reserva
--      • Único en toda la tabla (UNIQUE constraint)
--      • El QR solo se muestra al usuario mientras status = 'pending'
--      • Codifica la URL directa al detalle del préstamo en el panel admin
--        Ej: https://mibiblioteca.com/admin/loans/42
--
--    Restricción de edad:
--      • Si el libro tiene min_age > 0, se verifica que el usuario
--        tenga al menos esa edad calculada desde su birth_date
--      • Si el usuario no tiene birth_date registrado → no se bloquea
-- --------------------------------------------------------------------
CREATE TABLE loans (
    id          BIGINT UNSIGNED                                     AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED                                     NOT NULL,
    book_id     BIGINT UNSIGNED                                     NOT NULL,
    status      ENUM('pending','active','returned','overdue')       NOT NULL DEFAULT 'pending',
    loan_date   DATE                                                NOT NULL,
    return_date DATE                                                NULL,        -- Se rellena al marcar devuelto
    qr_token    VARCHAR(255)                                        NULL UNIQUE, -- UUID para verificación presencial
    created_at  TIMESTAMP                                           NULL,
    updated_at  TIMESTAMP                                           NULL,
    CONSTRAINT fk_loans_user FOREIGN KEY (user_id) REFERENCES book_store_users(id) ON DELETE CASCADE,
    CONSTRAINT fk_loans_book FOREIGN KEY (book_id) REFERENCES books(id)            ON DELETE CASCADE
);


-- --------------------------------------------------------------------
-- 7. Auditoría (audit_logs)
--
--    Registro inmutable de todos los eventos importantes del sistema.
--    Diseñado sin updated_at porque los logs no deben modificarse.
--    model_id puede ser NULL en eventos que no tienen modelo asociado
--    (por ejemplo, login_failed donde no hay ningún usuario autenticado).
--
--    ¿Quién genera estos registros?
--      • BookObserver   → 'created', 'updated', 'deleted' al gestionar libros
--      • AuthController → 'login_failed' al fallar un intento de login
--                         (guarda el email y la IP del intento)
--      • LoanController → 'updated' al confirmar entrega (pending → active)
--                         y al registrar devolución (→ returned)
--
--    Los últimos 8 registros se muestran en el dashboard del admin.
-- --------------------------------------------------------------------
CREATE TABLE audit_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NULL,
    action      VARCHAR(50)     NOT NULL,  -- created | updated | deleted | login_failed
    model_type  VARCHAR(100)    NOT NULL,  -- 'Book' | 'Loan' | 'Auth'
    model_id    BIGINT UNSIGNED NULL,
    description VARCHAR(255)    NOT NULL,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES book_store_users(id) ON DELETE SET NULL
);


-- ====================================================================
--  REGLAS DE NEGOCIO — RESUMEN COMPLETO
-- ====================================================================
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  STOCK FÍSICO                                                   │
--  │                                                                 │
--  │  stock_total      = copias físicas TOTALES de la biblioteca     │
--  │  available_copies = copias disponibles para préstamo AHORA      │
--  │                                                                 │
--  │  Invariante: 0 <= available_copies <= stock_total               │
--  │                                                                 │
--  │  Al RESERVAR (pending) → available_copies - 1                   │
--  │  Al DEVOLVER (returned) → available_copies + 1                  │
--  │  markReturned() protegido contra doble ejecución                │
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  FLUJO HÍBRIDO DE PRÉSTAMO (Web + Presencial + QR)              │
--  │                                                                 │
--  │  [web]        Usuario reserva    →  status = 'pending'          │
--  │                 stock decrementado en este paso                 │
--  │  [presencial] Muestra QR en biblioteca                          │
--  │  [admin]      Confirma entrega   →  status = 'active'           │
--  │  [presencial] Devuelve el libro                                 │
--  │  [admin]      Marca devuelto     →  status = 'returned'         │
--  │                 stock incrementado en este paso                 │
--  │  [excepción]  No devuelve        →  status = 'overdue' (manual) │
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  RESTRICCIÓN DE EDAD                                            │
--  │                                                                 │
--  │  Si min_age > 0 en el libro:                                    │
--  │    • Se calcula la edad del usuario desde birth_date            │
--  │    • Si edad calculada < min_age → se rechaza la reserva        │
--  │    • Si birth_date es NULL → se omite la validación (no bloquea)│
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  GUARD CONTRA RESERVAS DUPLICADAS                               │
--  │                                                                 │
--  │  Un usuario NO puede tener dos reservas en estado 'pending'     │
--  │  o 'active' del mismo libro al mismo tiempo.                    │
--  │  User\LoanController verifica esto antes de crear la reserva.   │
--  └─────────────────────────────────────────────────────────────────┘
--
-- ====================================================================
--  DATOS DE PRUEBA — Insertados por: php artisan migrate:fresh --seed
-- ====================================================================
--
--  ⚠️  Las contraseñas se hashean con bcrypt. Nunca insertes texto plano.
--      Usa siempre: php artisan migrate:fresh --seed
--      Eso ejecuta DatabaseSeeder que llama a Hash::make('password').
--
--  Los usuarios que genera el seeder son:
--
--    Nombre       | Email                    | Contraseña | Rol   | Activo
--    ─────────────┼──────────────────────────┼────────────┼───────┼───────
--    Admin Sistema | admin@biblioteca.com    | password   | admin | sí
--    User Sistema  | user@biblioteca.com     | password   | user  | sí
--
-- ====================================================================
