-- ====================================================================
--  REFERENCIA SQL — Biblioteca de Alejandría
--  Universidad UPED 2026 — Programación Aplicada 1
--  Actualizado: 2026-03-15
-- ====================================================================
--
--  ⚠️  IMPORTANTE: Este archivo es SOLO de referencia visual.
--      NO ejecutes estos CREATE TABLE manualmente en producción.
--      Las tablas las crea Laravel automáticamente con:
--
--          php artisan migrate
--      o
--          php artisan migrate:fresh --seed
--
--      Este script refleja el estado FINAL tras todas las migraciones.
-- ====================================================================


-- --------------------------------------------------------------------
-- 0. Crear y seleccionar la base de datos
-- --------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS biblioteca_alejandria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca_alejandria;


-- --------------------------------------------------------------------
-- 1. Usuarios del sistema (book_store_users)
-- --------------------------------------------------------------------
CREATE TABLE book_store_users (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name     VARCHAR(255)              NOT NULL,
    last_name      VARCHAR(255)              NOT NULL,
    email          VARCHAR(255)              NOT NULL UNIQUE,
    password       VARCHAR(255)              NOT NULL,
    role           ENUM('admin', 'user')     NOT NULL DEFAULT 'user',
    is_active      TINYINT(1)                NOT NULL DEFAULT 1,
    remember_token VARCHAR(100)              NULL,
    created_at     TIMESTAMP                 NULL,
    updated_at     TIMESTAMP                 NULL
);

-- Tablas auxiliares de Laravel (sesiones, tokens, caché, colas)
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
    key        VARCHAR(255) NOT NULL PRIMARY KEY,
    value      MEDIUMTEXT   NOT NULL,
    expiration INT          NOT NULL
);

CREATE TABLE jobs (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue        VARCHAR(255)    NOT NULL,
    payload      LONGTEXT        NOT NULL,
    attempts     TINYINT UNSIGNED NOT NULL,
    reserved_at  INT UNSIGNED    NULL,
    available_at INT UNSIGNED    NOT NULL,
    created_at   INT UNSIGNED    NOT NULL,
    INDEX jobs_queue_index (queue)
);


-- --------------------------------------------------------------------
-- 2. Categorías
-- --------------------------------------------------------------------
CREATE TABLE categories (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT         NULL,
    created_at  TIMESTAMP    NULL,
    updated_at  TIMESTAMP    NULL
);


-- --------------------------------------------------------------------
-- 3. Autores
-- --------------------------------------------------------------------
CREATE TABLE authors (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name  VARCHAR(255) NOT NULL,
    bio        TEXT         NULL,
    photo_path VARCHAR(255) NULL,   -- Ruta relativa en Storage::disk('public') → storage/app/public/authors/
    created_at TIMESTAMP    NULL,
    updated_at TIMESTAMP    NULL
);


-- --------------------------------------------------------------------
-- 4. Libros
--    Estado final tras migraciones (se eliminaron codigo_interno y path_pdf
--    en la migración 2026_03_15_000001)
-- --------------------------------------------------------------------
CREATE TABLE books (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id      BIGINT UNSIGNED NULL,
    title            VARCHAR(255)    NOT NULL,
    isbn             VARCHAR(20)     NULL UNIQUE,
    summary          TEXT            NULL,
    publisher        VARCHAR(150)    NULL,
    book_cover       VARCHAR(255)    NULL,   -- Ruta en Storage::disk('public') → storage/app/public/books/
    published_at     DATE            NULL,
    año              INT             NULL,   -- Derivado automáticamente de published_at al guardar
    stock_total      INT UNSIGNED    NOT NULL DEFAULT 0,
    available_copies INT UNSIGNED    NOT NULL DEFAULT 0,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);


-- --------------------------------------------------------------------
-- 5. Tabla pivote: Libros ↔ Autores (Muchos a Muchos)
-- --------------------------------------------------------------------
CREATE TABLE book_author (
    book_id   BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (book_id, author_id),
    CONSTRAINT fk_ba_book   FOREIGN KEY (book_id)   REFERENCES books(id)   ON DELETE CASCADE,
    CONSTRAINT fk_ba_author FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
);


-- --------------------------------------------------------------------
-- 6. Préstamos
-- --------------------------------------------------------------------
CREATE TABLE loans (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED                          NOT NULL,
    book_id     BIGINT UNSIGNED                          NOT NULL,
    status      ENUM('active', 'returned', 'overdue')   NOT NULL DEFAULT 'active',
    loan_date   DATE                                     NOT NULL,
    return_date DATE                                     NULL,
    created_at  TIMESTAMP                                NULL,
    updated_at  TIMESTAMP                                NULL,
    CONSTRAINT fk_loans_user FOREIGN KEY (user_id) REFERENCES book_store_users(id) ON DELETE CASCADE,
    CONSTRAINT fk_loans_book FOREIGN KEY (book_id) REFERENCES books(id)            ON DELETE CASCADE
);


-- --------------------------------------------------------------------
-- 7. Auditoría
--    Sin updated_at por diseño. model_id puede ser NULL (ej. login_failed).
-- --------------------------------------------------------------------
CREATE TABLE audit_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NULL,
    action      VARCHAR(50)     NOT NULL,   -- created | updated | deleted | login_failed
    model_type  VARCHAR(100)    NOT NULL,   -- 'Book' | 'Auth'
    model_id    BIGINT UNSIGNED NULL,
    description VARCHAR(255)    NOT NULL,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES book_store_users(id) ON DELETE SET NULL
);


-- ====================================================================
--  REGLAS DE NEGOCIO IMPORTANTES
-- ====================================================================
--
--  stock_total      = copias físicas totales de la biblioteca
--  available_copies = copias disponibles para préstamo ahora
--
--  Invariante: 0 <= available_copies <= stock_total
--
--  Al CREAR préstamo:
--      - Se verifica available_copies > 0 (error si es 0)
--      - Se ejecuta: UPDATE books SET available_copies = available_copies - 1
--
--  Al DEVOLVER préstamo:
--      - Se ejecuta: UPDATE books SET available_copies = available_copies + 1
--      - Protegido contra doble devolución (status = 'returned' no se procesa)
--
--  Al CREAR/EDITAR libro:
--      - available_copies no puede superar stock_total
--      - año se calcula automáticamente desde published_at en el controller
--
-- ====================================================================
--  DATOS DE PRUEBA (insertados por DatabaseSeeder con php artisan db:seed)
-- ====================================================================
--
--  INSERT INTO book_store_users (first_name, last_name, email, password, role, is_active)
--  VALUES
--      ('Admin',  'Sistema', 'admin@biblioteca.com', '<bcrypt_hash>', 'admin', 1),
--      ('Juan',   'Pérez',   'juan@biblioteca.com',  '<bcrypt_hash>', 'user',  1);
--
--  Nota: Las contraseñas se hashean con bcrypt. No insertes texto plano.
--        Usa php artisan migrate:fresh --seed para cargar los datos correctamente.
--
-- ====================================================================
