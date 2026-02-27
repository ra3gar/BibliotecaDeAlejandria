-- =============================================================
--  Biblioteca de Alejandría — Script MySQL
--  Universidad UPED 2026 · Programación Aplicada 1
--  Generado: 2026-02-26
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Base de datos
-- -------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `biblioteca_alejandria`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `biblioteca_alejandria`;

-- =============================================================
--  TABLAS DE INFRAESTRUCTURA LARAVEL
-- =============================================================

-- -------------------------------------------------------------
-- Tabla: book_store_users
-- -------------------------------------------------------------
CREATE TABLE `book_store_users` (
    `id`             BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `first_name`     VARCHAR(255)      NOT NULL,
    `last_name`      VARCHAR(255)      NOT NULL,
    `email`          VARCHAR(255)      NOT NULL,
    `password`       VARCHAR(255)      NOT NULL,
    `role`           ENUM('admin','user') NOT NULL DEFAULT 'user',
    `remember_token` VARCHAR(100)      NULL,
    `created_at`     TIMESTAMP         NULL,
    `updated_at`     TIMESTAMP         NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `book_store_users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: password_reset_tokens
-- -------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: sessions
-- -------------------------------------------------------------
CREATE TABLE `sessions` (
    `id`         VARCHAR(255) NOT NULL,
    `user_id`    BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45)  NULL,
    `user_agent` TEXT         NULL,
    `payload`    LONGTEXT     NOT NULL,
    `last_activity` INT       NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index`        (`user_id`),
    KEY `sessions_last_activity_index`  (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: cache
-- -------------------------------------------------------------
CREATE TABLE `cache` (
    `key`        VARCHAR(255)  NOT NULL,
    `value`      MEDIUMTEXT    NOT NULL,
    `expiration` INT           NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: cache_locks
-- -------------------------------------------------------------
CREATE TABLE `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: jobs
-- -------------------------------------------------------------
CREATE TABLE `jobs` (
    `id`           BIGINT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255)         NOT NULL,
    `payload`      LONGTEXT             NOT NULL,
    `attempts`     TINYINT UNSIGNED     NOT NULL,
    `reserved_at`  INT UNSIGNED         NULL,
    `available_at` INT UNSIGNED         NOT NULL,
    `created_at`   INT UNSIGNED         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: job_batches
-- -------------------------------------------------------------
CREATE TABLE `job_batches` (
    `id`             VARCHAR(255) NOT NULL,
    `name`           VARCHAR(255) NOT NULL,
    `total_jobs`     INT          NOT NULL,
    `pending_jobs`   INT          NOT NULL,
    `failed_jobs`    INT          NOT NULL,
    `failed_job_ids` LONGTEXT     NOT NULL,
    `options`        MEDIUMTEXT   NULL,
    `cancelled_at`   INT          NULL,
    `created_at`     INT          NOT NULL,
    `finished_at`    INT          NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: failed_jobs
-- -------------------------------------------------------------
CREATE TABLE `failed_jobs` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid`       VARCHAR(255)    NOT NULL,
    `connection` TEXT            NOT NULL,
    `queue`      TEXT            NOT NULL,
    `payload`    LONGTEXT        NOT NULL,
    `exception`  LONGTEXT        NOT NULL,
    `failed_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: migrations (requerida por Laravel)
-- -------------------------------------------------------------
CREATE TABLE `migrations` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch`     INT          NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
--  TABLAS DE DOMINIO
-- =============================================================

-- -------------------------------------------------------------
-- Tabla: categories
-- -------------------------------------------------------------
CREATE TABLE `categories` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100)    NOT NULL,
    `description` TEXT            NULL,
    `created_at`  TIMESTAMP       NULL,
    `updated_at`  TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: authors
-- -------------------------------------------------------------
CREATE TABLE `authors` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(100)    NOT NULL,
    `last_name`  VARCHAR(100)    NOT NULL,
    `bio`        TEXT            NULL,
    `created_at` TIMESTAMP       NULL,
    `updated_at` TIMESTAMP       NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: books
-- -------------------------------------------------------------
CREATE TABLE `books` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(255)    NOT NULL,
    `isbn`         VARCHAR(20)     NULL,
    `summary`      TEXT            NULL,
    `publisher`    VARCHAR(150)    NULL,
    `category_id`  BIGINT UNSIGNED NULL,
    `book_cover`   VARCHAR(255)    NULL,
    `published_at` DATE            NULL,
    `created_at`   TIMESTAMP       NULL,
    `updated_at`   TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `books_isbn_unique` (`isbn`),
    KEY `books_category_id_foreign` (`category_id`),
    CONSTRAINT `books_category_id_foreign`
        FOREIGN KEY (`category_id`)
        REFERENCES `categories` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: book_author  (pivote Libro ↔ Autor)
-- -------------------------------------------------------------
CREATE TABLE `book_author` (
    `book_id`   BIGINT UNSIGNED NOT NULL,
    `author_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`book_id`, `author_id`),
    KEY `book_author_author_id_foreign` (`author_id`),
    CONSTRAINT `book_author_book_id_foreign`
        FOREIGN KEY (`book_id`)
        REFERENCES `books` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `book_author_author_id_foreign`
        FOREIGN KEY (`author_id`)
        REFERENCES `authors` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: loans  (Préstamos)
-- -------------------------------------------------------------
CREATE TABLE `loans` (
    `id`          BIGINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
    `user_id`     BIGINT UNSIGNED                        NOT NULL,
    `book_id`     BIGINT UNSIGNED                        NOT NULL,
    `loan_date`   DATE                                   NOT NULL,
    `return_date` DATE                                   NULL,
    `status`      ENUM('active','returned','overdue')    NOT NULL DEFAULT 'active',
    `created_at`  TIMESTAMP                              NULL,
    `updated_at`  TIMESTAMP                              NULL,
    PRIMARY KEY (`id`),
    KEY `loans_user_id_foreign` (`user_id`),
    KEY `loans_book_id_foreign` (`book_id`),
    CONSTRAINT `loans_user_id_foreign`
        FOREIGN KEY (`user_id`)
        REFERENCES `book_store_users` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `loans_book_id_foreign`
        FOREIGN KEY (`book_id`)
        REFERENCES `books` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
--  DATOS DE EJEMPLO
--  Contraseña de todos los usuarios: password
-- =============================================================

INSERT INTO `book_store_users`
    (`first_name`, `last_name`, `email`, `password`, `role`, `created_at`, `updated_at`)
VALUES
    ('Admin',  'Sistema', 'admin@biblioteca.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'admin', NOW(), NOW()),
    ('Juan',   'Pérez',   'juan@biblioteca.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'user',  NOW(), NOW()),
    ('María',  'García',  'maria@biblioteca.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'user',  NOW(), NOW());

INSERT INTO `categories` (`name`, `description`, `created_at`, `updated_at`) VALUES
    ('Ciencia Ficción',  'Narrativa especulativa sobre futuros posibles y tecnología avanzada.',                 NOW(), NOW()),
    ('Historia',         'Obras sobre eventos y personajes del pasado.',                                        NOW(), NOW()),
    ('Filosofía',        'Reflexiones sobre la existencia, el conocimiento y la ética.',                        NOW(), NOW()),
    ('Literatura',       'Clásicos y obras de ficción general.',                                                NOW(), NOW()),
    ('Ciencias',         'Divulgación científica y textos técnicos.',                                           NOW(), NOW()),
    ('Biografías',       'Vida de personajes históricos y contemporáneos relevantes.',                          NOW(), NOW());

INSERT INTO `authors` (`first_name`, `last_name`, `bio`, `created_at`, `updated_at`) VALUES
    ('Gabriel', 'García Márquez', 'Escritor colombiano, Premio Nobel de Literatura 1982. Padre del realismo mágico.', NOW(), NOW()),
    ('Jorge Luis', 'Borges',      'Escritor argentino, ensayista y poeta. Una de las figuras centrales de la literatura hispanoamericana.', NOW(), NOW()),
    ('Isaac', 'Asimov',           'Escritor y bioquímico estadounidense de origen ruso, prolífico autor de ciencia ficción.', NOW(), NOW()),
    ('Yuval Noah', 'Harari',      'Historiador y autor israelí, conocido por sus obras de divulgación sobre historia y futuro humano.', NOW(), NOW()),
    ('Isabel', 'Allende',         'Escritora chilena, una de las autoras más leídas en lengua española.', NOW(), NOW());

INSERT INTO `books`
    (`title`, `isbn`, `summary`, `publisher`, `category_id`, `published_at`, `created_at`, `updated_at`)
VALUES
    ('Cien años de soledad',
     '978-0-06-088328-7',
     'La historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.',
     'Editorial Sudamericana', 4, '1967-05-30', NOW(), NOW()),

    ('Ficciones',
     '978-0-8021-3234-7',
     'Colección de cuentos que exploran laberintos, espejos y juegos metafísicos.',
     'Editorial Sur', 4, '1944-01-01', NOW(), NOW()),

    ('El fin de la eternidad',
     '978-0-586-02454-6',
     'Un agente de Eternidad viaja en el tiempo para modificar la historia de la humanidad.',
     'Doubleday', 1, '1955-08-01', NOW(), NOW()),

    ('Sapiens: De animales a dioses',
     '978-0-06-231609-7',
     'Breve historia de la humanidad desde el surgimiento del Homo sapiens hasta el presente.',
     'Harper Collins', 2, '2011-01-01', NOW(), NOW()),

    ('La casa de los espíritus',
     '978-0-553-38380-1',
     'Saga familiar que abarca varias generaciones marcadas por el amor, la magia y la política.',
     'Plaza & Janés', 4, '1982-01-01', NOW(), NOW());

-- Relaciones libro ↔ autor
INSERT INTO `book_author` (`book_id`, `author_id`) VALUES
    (1, 1),  -- Cien años de soledad → García Márquez
    (2, 2),  -- Ficciones → Borges
    (3, 3),  -- El fin de la eternidad → Asimov
    (4, 4),  -- Sapiens → Harari
    (5, 5);  -- La casa de los espíritus → Allende

-- Préstamos de ejemplo
INSERT INTO `loans` (`user_id`, `book_id`, `loan_date`, `return_date`, `status`, `created_at`, `updated_at`) VALUES
    (2, 1, '2026-02-01', NULL,         'active',   NOW(), NOW()),
    (2, 3, '2026-01-10', '2026-01-25', 'returned', NOW(), NOW()),
    (3, 4, '2026-02-15', NULL,         'active',   NOW(), NOW()),
    (3, 2, '2026-01-05', NULL,         'overdue',  NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================
--  FIN DEL SCRIPT
--  Para importar: mysql -u root -p biblioteca_alejandria < biblioteca_alejandria.sql
--  O desde MySQL Workbench: File > Run SQL Script
-- =============================================================
