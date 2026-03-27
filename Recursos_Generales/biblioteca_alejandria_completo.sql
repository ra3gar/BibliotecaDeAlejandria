-- ====================================================================
--  SCRIPT COMPLETO — Biblioteca de Alejandría
--  Universidad UPED 2026 · Programación Aplicada 1
--  Consolidado: 2026-03-22
-- ====================================================================
--
--  Este archivo hace DOS cosas en orden:
--    1. Crea todas las tablas (esquema completo, 17 migraciones)
--    2. Inserta datos de prueba listos para usar en desarrollo
--
--  Cómo usarlo:
--    a) Terminal:   mysql -u root -p < biblioteca_alejandria_completo.sql
--    b) Workbench:  File → Run SQL Script → selecciona este archivo
--    c) phpMyAdmin: Importar → selecciona este archivo
--
--  ⚠️  ADVERTENCIAS:
--    • Requiere que MySQL esté corriendo y que tengas credenciales
--    • Si la BD ya existe, DROP DATABASE la eliminará con todos sus datos
--    • Solo para DESARROLLO — nunca ejecutes en producción
--    • La forma preferida de inicializar es:
--        php artisan migrate:fresh --seed
--      (genera contraseñas con Hash::make() y respeta las migraciones)
--
-- ====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;   -- Desactiva chequeo de FK para inserción sin orden estricto
SET time_zone = '+00:00';


-- --------------------------------------------------------------------
-- BASE DE DATOS
--
-- utf8mb4 es el único charset MySQL que cubre el Unicode completo:
-- tildes, ñ, comillas tipográficas y emojis sin corrupción de datos.
-- utf8mb4_unicode_ci ordena sin distinguir mayúsculas ("Álvarez" = "alvarez").
-- --------------------------------------------------------------------
DROP DATABASE IF EXISTS biblioteca_alejandria;

CREATE DATABASE biblioteca_alejandria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca_alejandria;


-- ====================================================================
--  SECCIÓN 1: TABLAS DE INFRAESTRUCTURA LARAVEL
--  El framework las necesita para funcionar. No contienen lógica
--  de negocio propia — son el "motor" de sesiones, caché y colas.
-- ====================================================================

-- --------------------------------------------------------------------
-- Registro de migraciones
-- Laravel registra aquí cada migración ejecutada para saber cuáles
-- ya corrió y cuáles están pendientes. Sin esta tabla, migrate falla.
-- --------------------------------------------------------------------
CREATE TABLE migrations (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    migration VARCHAR(255) NOT NULL,
    batch     INT          NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Tokens de restablecimiento de contraseña
-- Almacena los enlaces "olvidé mi contraseña" hasta que expiran.
-- En este proyecto no se usa la funcionalidad de reset, pero la
-- migración base de Laravel la crea igualmente.
-- --------------------------------------------------------------------
CREATE TABLE password_reset_tokens (
    email      VARCHAR(255) NOT NULL,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP    NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Sesiones de usuario
-- Laravel serializa aquí la información de cada sesión activa cuando
-- SESSION_DRIVER=database está configurado en el .env.
-- Los índices aceleran las búsquedas por usuario y por expiración.
-- --------------------------------------------------------------------
CREATE TABLE sessions (
    id            VARCHAR(255)    NOT NULL,
    user_id       BIGINT UNSIGNED NULL,
    ip_address    VARCHAR(45)     NULL,      -- VARCHAR(45) soporta IPv6 completo
    user_agent    TEXT            NULL,
    payload       LONGTEXT        NOT NULL,
    last_activity INT             NOT NULL,
    PRIMARY KEY (id),
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Caché de la aplicación
-- Almacena resultados cacheados para no recalcularlos en cada request.
-- Nota: se usa cache_key (no "key") porque KEY es palabra reservada MySQL.
-- --------------------------------------------------------------------
CREATE TABLE cache (
    cache_key  VARCHAR(255) NOT NULL,
    value      MEDIUMTEXT   NOT NULL,
    expiration INT          NOT NULL,
    PRIMARY KEY (cache_key),
    INDEX cache_expiration_index (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Bloqueos de caché (cache_locks)
-- Garantiza operaciones atómicas sobre entradas de caché cuando
-- múltiples procesos intentan escribir al mismo tiempo.
-- --------------------------------------------------------------------
CREATE TABLE cache_locks (
    cache_key  VARCHAR(255) NOT NULL,
    owner      VARCHAR(255) NOT NULL,
    expiration INT          NOT NULL,
    PRIMARY KEY (cache_key),
    INDEX cache_locks_expiration_index (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Cola de trabajos en background (jobs)
-- Los jobs se encolan aquí y php artisan queue:listen los consume.
-- Ejemplos de uso: envío de emails, generación de reportes pesados.
-- --------------------------------------------------------------------
CREATE TABLE jobs (
    id           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    queue        VARCHAR(255)     NOT NULL,
    payload      LONGTEXT         NOT NULL,
    attempts     TINYINT UNSIGNED NOT NULL DEFAULT 0,
    reserved_at  INT UNSIGNED     NULL,
    available_at INT UNSIGNED     NOT NULL,
    created_at   INT UNSIGNED     NOT NULL,
    PRIMARY KEY (id),
    INDEX jobs_queue_index (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Lotes de jobs (job_batches)
-- Permite agrupar múltiples jobs y monitorear su progreso colectivo.
-- Ejemplo: "procesar 500 imágenes" como un lote con seguimiento.
-- --------------------------------------------------------------------
CREATE TABLE job_batches (
    id             VARCHAR(255) NOT NULL,
    name           VARCHAR(255) NOT NULL,
    total_jobs     INT          NOT NULL,
    pending_jobs   INT          NOT NULL,
    failed_jobs    INT          NOT NULL,
    failed_job_ids LONGTEXT     NOT NULL,
    options        MEDIUMTEXT   NULL,
    cancelled_at   INT          NULL,
    created_at     INT          NOT NULL,
    finished_at    INT          NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Jobs fallidos (failed_jobs)
-- Si un job falla después de todos sus reintentos, Laravel lo archiva
-- aquí con la excepción completa para diagnóstico posterior.
-- --------------------------------------------------------------------
CREATE TABLE failed_jobs (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    uuid       VARCHAR(255)    NOT NULL,
    connection TEXT            NOT NULL,
    queue      TEXT            NOT NULL,
    payload    LONGTEXT        NOT NULL,
    exception  LONGTEXT        NOT NULL,
    failed_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY failed_jobs_uuid_unique (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ====================================================================
--  SECCIÓN 2: TABLAS DE DOMINIO — Biblioteca de Alejandría
--  Aquí vive la lógica de negocio del sistema.
--  Orden de creación: respeta las dependencias de foreign keys.
-- ====================================================================

-- --------------------------------------------------------------------
-- 1. USUARIOS DEL SISTEMA (book_store_users)
--
--  Nombre personalizado para evitar colisiones con proyectos Laravel
--  que también usen una tabla "users" en la misma BD.
--
--  Roles:
--    admin → panel completo en /admin (CRUD + préstamos + auditoría)
--    user  → catálogo /catalogo + perfil /perfil + reservas
--
--  is_active: el middleware CheckRole detecta is_active = 0 en cada
--  request y desconecta al usuario automáticamente → redirect al login.
--
--  birth_date: cuando un libro tiene min_age > 0, se calcula la edad
--  del usuario desde este campo. Si es NULL, la validación se omite.
-- --------------------------------------------------------------------
CREATE TABLE book_store_users (
    id             BIGINT UNSIGNED      NOT NULL AUTO_INCREMENT,
    first_name     VARCHAR(255)         NOT NULL,
    last_name      VARCHAR(255)         NOT NULL,
    email          VARCHAR(255)         NOT NULL,
    password       VARCHAR(255)         NOT NULL,           -- Hash bcrypt(cost≥10). NUNCA texto plano.
    role           ENUM('admin','user') NOT NULL DEFAULT 'user',
    is_active      TINYINT(1)           NOT NULL DEFAULT 1, -- 1 = activo, 0 = bloqueado por admin
    birth_date     DATE                 NULL,               -- Para restricción min_age en préstamos
    remember_token VARCHAR(100)         NULL,               -- Token "recuérdame" de Laravel
    created_at     TIMESTAMP            NULL,
    updated_at     TIMESTAMP            NULL,
    PRIMARY KEY (id),
    UNIQUE KEY book_store_users_email_unique (email),
    INDEX book_store_users_role_index (role),               -- Listar solo admins o solo usuarios
    INDEX book_store_users_is_active_index (is_active)      -- Alertas de cuentas bloqueadas en dashboard
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------
-- 2. CATEGORÍAS (categories)
--
--  Clasificación temática del catálogo: Ciencia Ficción, Historia,
--  Filosofía, Literatura, etc.
--
--  Un libro pertenece a UNA categoría (o ninguna si category_id NULL).
--  Si se elimina la categoría, los libros mantienen su registro pero
--  quedan sin categoría gracias a ON DELETE SET NULL.
-- --------------------------------------------------------------------
CREATE TABLE categories (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(255)    NOT NULL,
    description TEXT            NULL,
    created_at  TIMESTAMP       NULL,
    updated_at  TIMESTAMP       NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------
-- 3. AUTORES (authors)
--
--  Un libro puede tener N autores; un autor puede tener N libros.
--  Relación N:M implementada por la tabla pivote book_author.
--
--  Gestión de foto:
--    • Se almacena en storage/app/public/authors/
--    • $author->photo_url devuelve la URL pública o null si no hay foto
--    • Al ACTUALIZAR foto → la anterior se borra del disco automáticamente
--    • Al ELIMINAR autor → su foto se borra del disco automáticamente
--
--  Formatos: jpeg, jpg, png | Tamaño máximo: 2 MB
-- --------------------------------------------------------------------
CREATE TABLE authors (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(255)    NOT NULL,
    last_name  VARCHAR(255)    NOT NULL,
    bio        TEXT            NULL,         -- Biografía larga, se muestra en la ficha del libro
    photo_path VARCHAR(255)    NULL,         -- Ruta relativa: "authors/foto_apellido.jpg"
    created_at TIMESTAMP       NULL,
    updated_at TIMESTAMP       NULL,
    PRIMARY KEY (id),
    INDEX authors_last_name_index (last_name)  -- Filtrado por autor en el catálogo
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------
-- 4. LIBROS (books)
--
--  Cada fila es un TÍTULO único (no un ejemplar físico individual).
--  Los ejemplares se cuentan con stock_total y available_copies.
--
--  INVARIANTE DE STOCK — siempre debe cumplirse:
--    0 <= available_copies <= stock_total
--
--  Campo "año": derivado de published_at en BookController al guardar.
--  El formulario no lo envía — se calcula automáticamente.
--
--  Campo "min_age": edad mínima en años para solicitar el préstamo.
--    0 = sin restricción (cualquier usuario puede reservar).
--
--  Campos eliminados en migración 2026_03_15_000001:
--    codigo_interno → heredado, nunca tuvo uso real
--    path_pdf       → préstamos digitales descartados del alcance
-- --------------------------------------------------------------------
CREATE TABLE books (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id      BIGINT UNSIGNED NULL,
    title            VARCHAR(255)    NOT NULL,
    isbn             VARCHAR(20)     NULL,     -- ISBN-10 o ISBN-13, único si se provee
    summary          TEXT            NULL,     -- Sinopsis del libro
    publisher        VARCHAR(150)    NULL,     -- Editorial: "Planeta", "Anagrama", etc.
    book_cover       VARCHAR(255)    NULL,     -- Ruta: "books/portada_titulo.jpg"
    published_at     DATE            NULL,     -- Fecha de publicación
    año              INT             NULL,     -- Derivado de published_at al guardar (no lo envía el form)
    stock_total      INT UNSIGNED    NOT NULL DEFAULT 0,  -- Ejemplares físicos totales en biblioteca
    available_copies INT UNSIGNED    NOT NULL DEFAULT 0,  -- Disponibles para préstamo ahora mismo
    min_age          INT UNSIGNED    NOT NULL DEFAULT 0,  -- Edad mínima (años). 0 = sin restricción
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    PRIMARY KEY (id),
    UNIQUE KEY books_isbn_unique (isbn),
    INDEX books_category_id_index (category_id),          -- Filtrado del catálogo por categoría
    INDEX books_available_copies_index (available_copies), -- Buscar libros con stock > 0
    INDEX books_min_age_index (min_age),                  -- Filtrar por restricción de edad
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------
-- 5. TABLA PIVOTE: LIBROS ↔ AUTORES (book_author)
--
--  García Márquez puede aparecer en 10 libros.
--  "El Quijote" puede tener a Cervantes como único autor.
--  Esta tabla hace posible ambas cosas.
--
--  Sin id propio ni timestamps — es una tabla de relaciones pura.
--  Se gestiona en Laravel con: $book->authors()->sync([1, 3, 7]);
--  CASCADE ON DELETE evita filas huérfanas al borrar libros o autores.
-- --------------------------------------------------------------------
CREATE TABLE book_author (
    book_id   BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (book_id, author_id),
    INDEX book_author_author_id_index (author_id),    -- "Todos los libros de este autor"
    CONSTRAINT fk_ba_book
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_ba_author
        FOREIGN KEY (author_id)
        REFERENCES authors(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------
-- 6. PRÉSTAMOS (loans)
--
--  La tabla más dinámica del sistema. Cada fila es un préstamo físico
--  con su ciclo de vida completo gestionado por el campo "status".
--
--  CICLO DE VIDA:
--  ──────────────────────────────────────────────────────────────────
--  [1] RESERVA ONLINE  → status = 'pending'
--      • Se genera qr_token (UUID único con Str::uuid())
--      • available_copies - 1  (stock se aparta en este momento)
--
--  [2] VERIFICACIÓN PRESENCIAL
--      • Usuario muestra QR en la biblioteca
--      • Admin escanea o busca manualmente el préstamo
--
--  [3] ENTREGA FÍSICA → status = 'active'
--      • Admin confirma entrega en el sistema
--      • Se registra en audit_logs
--
--  [4] DEVOLUCIÓN → status = 'returned'
--      • Usuario regresa el libro físico
--      • Admin marca devuelto en el sistema
--      • available_copies + 1  (stock se libera en este momento)
--      • Se registra en audit_logs
--      • Protección anti-doble-devolución en markReturned()
--
--  [E] VENCIDO → status = 'overdue'
--      • Admin lo marca manualmente si no devuelven
--      • El stock NO cambia (el libro sigue "fuera")
--  ──────────────────────────────────────────────────────────────────
--  GUARD ANTI-DUPLICADOS: un usuario no puede tener dos reservas
--  'pending' o 'active' del mismo libro al mismo tiempo.
-- --------------------------------------------------------------------
CREATE TABLE loans (
    id          BIGINT UNSIGNED                                NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED                                NOT NULL,
    book_id     BIGINT UNSIGNED                                NOT NULL,
    status      ENUM('pending','active','returned','overdue')  NOT NULL DEFAULT 'pending',
    loan_date   DATE                                           NOT NULL, -- Fecha de la reserva online
    return_date DATE                                           NULL,     -- Se rellena al marcar devuelto
    qr_token    VARCHAR(255)                                   NULL,     -- UUID para verificación presencial
    created_at  TIMESTAMP                                      NULL,
    updated_at  TIMESTAMP                                      NULL,
    PRIMARY KEY (id),
    UNIQUE KEY loans_qr_token_unique (qr_token),
    INDEX loans_user_id_status_index (user_id, status),  -- Guard anti-duplicados + "mis préstamos activos"
    INDEX loans_book_id_status_index (book_id, status),  -- Disponibilidad de un título específico
    INDEX loans_status_index (status),                   -- Dashboard: contar activos, vencidos, etc.
    CONSTRAINT fk_loans_user
        FOREIGN KEY (user_id)
        REFERENCES book_store_users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_loans_book
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------------------
-- 7. REGISTRO DE AUDITORÍA (audit_logs)
--
--  El guardián inmutable del sistema. Todo evento relevante queda
--  registrado con quién lo hizo, qué fue, cuándo y sobre qué objeto.
--
--  SIN updated_at por diseño — los logs son inmutables.
--  No deben modificarse nunca tras su creación.
--
--  Generado por:
--    BookObserver   → 'created', 'updated', 'deleted' al gestionar libros
--    AuthController → 'login_failed' (guarda email + IP del intento)
--    LoanController → 'updated' al confirmar entrega (pending → active)
--                     'updated' al registrar devolución (→ returned)
--
--  Los últimos 8 registros se muestran en el dashboard del admin.
-- --------------------------------------------------------------------
CREATE TABLE audit_logs (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED NULL,              -- NULL si no hay usuario autenticado (login_failed)
    action      VARCHAR(50)     NOT NULL,          -- 'created' | 'updated' | 'deleted' | 'login_failed'
    model_type  VARCHAR(100)    NOT NULL,          -- 'Book' | 'Loan' | 'Auth'
    model_id    BIGINT UNSIGNED NULL,              -- NULL en login_failed (no hay objeto Eloquent)
    description VARCHAR(255)    NOT NULL,          -- Texto legible: "Libro 'El Principito' creado"
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX audit_logs_created_at_index (created_at DESC), -- Dashboard: últimos N registros
    INDEX audit_logs_action_index (action),              -- Filtrar por tipo de evento
    INDEX audit_logs_user_id_index (user_id),            -- Historial de un usuario específico
    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id)
        REFERENCES book_store_users(id)
        ON DELETE SET NULL   -- El log se conserva aunque se elimine el usuario
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ====================================================================
--  SECCIÓN 3: DATOS DE PRUEBA
--
--  Listos para desarrollo y demostración del sistema.
--  Contraseña de todos los usuarios: password
--  (hash bcrypt cost=12 — válido solo para desarrollo)
--
--  Para regenerar con el seeder oficial de Laravel:
--    php artisan migrate:fresh --seed
-- ====================================================================

-- --------------------------------------------------------------------
-- Usuarios del sistema
-- admin → panel completo | user → catálogo y perfil
-- --------------------------------------------------------------------
INSERT INTO book_store_users
    (first_name, last_name, email, password, role, is_active, birth_date, created_at, updated_at)
VALUES
    (
        'Admin', 'Sistema',
        'admin@biblioteca.com',
        '$2y$12$Nk/9YEf8GzKgHnjB2VWv9uVCXwy9HI9pGYbCf66aKX.FccAxHTlMu',  -- "password"
        'admin', 1, NULL, NOW(), NOW()
    ),
    (
        'User', 'Sistema',
        'user@biblioteca.com',
        '$2y$12$wKtYKt0DuVjNgEG88lqp1u/f5tHx3a1y.ZKP6Zpbqwnz0inx/R83y',  -- "password"
        'user', 1, NULL, NOW(), NOW()   -- birth_date NULL: validación de edad se omite
    );

-- --------------------------------------------------------------------
-- Categorías del catálogo
-- --------------------------------------------------------------------
INSERT INTO categories (name, description, created_at, updated_at) VALUES
    ('Ciencia Ficción', 'Narrativa especulativa sobre futuros posibles y tecnología avanzada.',          NOW(), NOW()),
    ('Historia',        'Obras sobre eventos y personajes del pasado.',                                 NOW(), NOW()),
    ('Filosofía',       'Reflexiones sobre la existencia, el conocimiento y la ética.',                 NOW(), NOW()),
    ('Literatura',      'Clásicos y obras de ficción general.',                                         NOW(), NOW()),
    ('Ciencias',        'Divulgación científica y textos técnicos.',                                    NOW(), NOW()),
    ('Biografías',      'Vida de personajes históricos y contemporáneos relevantes.',                   NOW(), NOW());

-- --------------------------------------------------------------------
-- Autores
-- --------------------------------------------------------------------
INSERT INTO authors (first_name, last_name, bio, photo_path, created_at, updated_at) VALUES
    ('Gabriel',    'García Márquez', 'Escritor colombiano, Premio Nobel de Literatura 1982. Padre del realismo mágico.',                                    NULL, NOW(), NOW()),
    ('Jorge Luis', 'Borges',         'Escritor argentino, ensayista y poeta. Una de las figuras centrales de la literatura hispanoamericana.',               NULL, NOW(), NOW()),
    ('Isaac',      'Asimov',         'Escritor y bioquímico estadounidense de origen ruso, prolífico autor de ciencia ficción.',                            NULL, NOW(), NOW()),
    ('Yuval Noah', 'Harari',         'Historiador y autor israelí, conocido por sus obras de divulgación sobre historia y futuro humano.',                  NULL, NOW(), NOW()),
    ('Isabel',     'Allende',        'Escritora chilena, una de las autoras más leídas en lengua española.',                                               NULL, NOW(), NOW());

-- --------------------------------------------------------------------
-- Libros
-- stock_total y available_copies reflejan una biblioteca en operación:
-- algunos libros tienen todos sus ejemplares disponibles, otros no.
-- min_age = 0 en todos → sin restricción de edad para estas pruebas.
-- El campo "año" se deja NULL (lo calcula BookController al guardar via Artisan).
-- --------------------------------------------------------------------
INSERT INTO books
    (title, isbn, summary, publisher, category_id, published_at, año,
     stock_total, available_copies, min_age, created_at, updated_at)
VALUES
    (
        'Cien años de soledad',
        '978-0-06-088328-7',
        'La historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.',
        'Editorial Sudamericana', 4, '1967-05-30', 1967,
        3, 2, 0,   -- 3 ejemplares totales, 2 disponibles (1 está prestado)
        NOW(), NOW()
    ),
    (
        'Ficciones',
        '978-0-8021-3234-7',
        'Colección de cuentos que exploran laberintos, espejos y juegos metafísicos.',
        'Editorial Sur', 4, '1944-01-01', 1944,
        2, 1, 0,   -- 2 ejemplares, 1 disponible (1 en préstamo vencido)
        NOW(), NOW()
    ),
    (
        'El fin de la eternidad',
        '978-0-586-02454-6',
        'Un agente de Eternidad viaja en el tiempo para modificar la historia de la humanidad.',
        'Doubleday', 1, '1955-08-01', 1955,
        2, 2, 0,   -- 2 ejemplares, ambos disponibles (el préstamo fue devuelto)
        NOW(), NOW()
    ),
    (
        'Sapiens: De animales a dioses',
        '978-0-06-231609-7',
        'Breve historia de la humanidad desde el surgimiento del Homo sapiens hasta el presente.',
        'Harper Collins', 2, '2011-01-01', 2011,
        4, 3, 0,   -- 4 ejemplares, 3 disponibles (1 pendiente de recojo)
        NOW(), NOW()
    ),
    (
        'La casa de los espíritus',
        '978-0-553-38380-1',
        'Saga familiar que abarca varias generaciones marcadas por el amor, la magia y la política.',
        'Plaza & Janés', 4, '1982-01-01', 1982,
        3, 3, 0,   -- 3 ejemplares, todos disponibles
        NOW(), NOW()
    );

-- --------------------------------------------------------------------
-- Relaciones libro ↔ autor (tabla pivote)
-- --------------------------------------------------------------------
INSERT INTO book_author (book_id, author_id) VALUES
    (1, 1),  -- Cien años de soledad  → García Márquez
    (2, 2),  -- Ficciones             → Borges
    (3, 3),  -- El fin de la eternidad → Asimov
    (4, 4),  -- Sapiens               → Harari
    (5, 5);  -- La casa de los espíritus → Allende

-- --------------------------------------------------------------------
-- Préstamos de ejemplo — flujo híbrido completo
--
-- Se representan los 4 estados posibles del ciclo de vida:
--
--   'pending'  → reserva web realizada, pendiente de recojo presencial
--                stock ya fue decrementado al crear la reserva
--   'active'   → entrega física confirmada por el admin con QR
--   'returned' → libro devuelto, stock ya fue incrementado
--   'overdue'  → no devolvió a tiempo, marcado manualmente por admin
--
-- qr_token: UUIDs estáticos de ejemplo (en producción los genera
-- Str::uuid() de Laravel al crear cada reserva).
-- --------------------------------------------------------------------
INSERT INTO loans
    (user_id, book_id, status, loan_date, return_date, qr_token, created_at, updated_at)
VALUES
    (
        2, 4,           -- user@biblioteca.com reservó "Sapiens"
        'pending',
        '2026-03-20',   -- Reservó hace 2 días
        NULL,           -- Aún no ha recogido el libro
        'a1b2c3d4-e5f6-7890-abcd-ef1234567890',  -- QR visible en /perfil
        NOW(), NOW()
    ),
    (
        2, 1,           -- user@biblioteca.com tiene "Cien años de soledad"
        'active',
        '2026-03-10',   -- Reservó el 10 de marzo
        NULL,           -- No ha devuelto aún
        'b2c3d4e5-f6a7-8901-bcde-f12345678901',
        NOW(), NOW()
    ),
    (
        2, 3,           -- user@biblioteca.com devolvió "El fin de la eternidad"
        'returned',
        '2026-02-01',   -- Reservó el 1 de febrero
        '2026-02-20',   -- Devolvió el 20 de febrero (stock++ en ese momento)
        'c3d4e5f6-a7b8-9012-cdef-123456789012',
        NOW(), NOW()
    ),
    (
        2, 2,           -- user@biblioteca.com tiene "Ficciones" vencido
        'overdue',
        '2026-01-05',   -- Reservó en enero, nunca devolvió
        NULL,
        'd4e5f6a7-b8c9-0123-defa-234567890123',
        NOW(), NOW()
    );

-- ====================================================================
--  SECCIÓN 4: REGISTROS DE AUDITORÍA INICIALES
--  Muestra cómo se verá el log en el dashboard del admin.
-- ====================================================================
INSERT INTO audit_logs (user_id, action, model_type, model_id, description, created_at) VALUES
    (1, 'created', 'Book', 1, "Libro 'Cien años de soledad' registrado en el catálogo",        NOW()),
    (1, 'created', 'Book', 2, "Libro 'Ficciones' registrado en el catálogo",                   NOW()),
    (1, 'created', 'Book', 3, "Libro 'El fin de la eternidad' registrado en el catálogo",      NOW()),
    (1, 'created', 'Book', 4, "Libro 'Sapiens: De animales a dioses' registrado en el catálogo", NOW()),
    (1, 'created', 'Book', 5, "Libro 'La casa de los espíritus' registrado en el catálogo",    NOW()),
    (1, 'updated', 'Loan', 2, "Préstamo #2 confirmado: entrega de 'Cien años de soledad' a User Sistema", NOW()),
    (1, 'updated', 'Loan', 3, "Préstamo #3 devuelto: 'El fin de la eternidad' regresado por User Sistema", NOW()),
    (NULL, 'login_failed', 'Auth', NULL, "Intento de login fallido — email: intruso@hack.com — IP: 192.168.1.100", NOW());


SET FOREIGN_KEY_CHECKS = 1;


-- ====================================================================
--  RESUMEN DE REGLAS DE NEGOCIO
-- ====================================================================
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  STOCK FÍSICO                                                   │
--  │  stock_total      = copias físicas TOTALES en biblioteca        │
--  │  available_copies = copias disponibles para préstamo AHORA      │
--  │  INVARIANTE: 0 <= available_copies <= stock_total               │
--  │  Al RESERVAR (→ 'pending')  : available_copies - 1              │
--  │  Al DEVOLVER (→ 'returned') : available_copies + 1              │
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  FLUJO HÍBRIDO (Web + Presencial + QR)                          │
--  │  [web]        reserva online    → status = 'pending'  stock--   │
--  │  [presencial] muestra QR en biblioteca                          │
--  │  [admin]      confirma entrega  → status = 'active'             │
--  │  [presencial] devuelve libro                                    │
--  │  [admin]      marca devuelto    → status = 'returned' stock++   │
--  │  [excepción]  no devuelve       → status = 'overdue' (manual)   │
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  RESTRICCIÓN DE EDAD (min_age)                                  │
--  │  Si books.min_age > 0: se valida edad desde birth_date          │
--  │  Si birth_date es NULL: validación se omite (no bloquea)        │
--  └─────────────────────────────────────────────────────────────────┘
--
-- ====================================================================
--  ACCESOS DE PRUEBA
-- ====================================================================
--
--  Email                   | Contraseña | Rol   | URL de acceso
--  ────────────────────────┼────────────┼───────┼──────────────────────
--  admin@biblioteca.com    | password   | admin | /admin/dashboard
--  user@biblioteca.com     | password   | user  | /catalogo
--
--  Para importar este script:
--    Terminal:   mysql -u root -p < biblioteca_alejandria_completo.sql
--    Workbench:  File → Run SQL Script
--    phpMyAdmin: Importar → selecciona este archivo
--
-- ====================================================================
