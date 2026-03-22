-- ====================================================================
--  REFERENCIA SQL — Biblioteca de Alejandría
--  Universidad UPED 2026 — Programación Aplicada 1
--  Actualizado: 2026-03-22
-- ====================================================================
--
--  ⚠️  ESTE ARCHIVO ES SOLO DE REFERENCIA VISUAL — NO EJECUTAR EN
--      PRODUCCIÓN NI EN DESARROLLO.
--
--      Las tablas las crea y gestiona Laravel automáticamente con
--      sus migraciones (database/migrations/). Nunca las crees a mano.
--
--      Comandos legítimos para inicializar la base de datos:
--
--          php artisan migrate              → aplica las migraciones pendientes
--          php artisan migrate:fresh --seed → borra todo y recrea desde cero
--                                             (solo en desarrollo, ¡destruye datos!)
--
--      Este script refleja el estado FINAL tras las 17 migraciones
--      disponibles en database/migrations/ y sirve para:
--        • Entender la estructura de la BD sin abrir phpMyAdmin
--        • Documentar el esquema para revisiones de código o entrega
--        • Depurar problemas de foreign keys o tipos de datos
--
-- ====================================================================


-- --------------------------------------------------------------------
-- 0. CREAR Y SELECCIONAR LA BASE DE DATOS
--
--    Aquí nace nuestra biblioteca digital. Antes de guardar un solo
--    libro o usuario, necesitamos un espacio donde vivirán todos los
--    datos del sistema.
--
--    ¿Por qué utf8mb4 y no utf8?
--    MySQL llama "utf8" a una implementación incompleta que solo cubre
--    3 bytes por carácter y NO soporta emojis ni algunos caracteres
--    asiáticos. utf8mb4 es la implementación real de UTF-8 (4 bytes)
--    y soporta tildes (á, é, ü, ñ), comillas tipográficas y cualquier
--    emoji sin corrupción de datos.
--
--    utf8mb4_unicode_ci garantiza que "Álvarez" y "alvarez" sean
--    considerados iguales al comparar o buscar (case-insensitive).
-- --------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS biblioteca_alejandria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca_alejandria;


-- ====================================================================
--  TABLAS AUXILIARES DE LARAVEL
--  Generadas por las migraciones base del framework. Se incluyen
--  aquí solo por completitud documental — Laravel las maneja solas.
-- ====================================================================

-- --------------------------------------------------------------------
-- Tokens de restablecimiento de contraseña
-- Almacena los enlaces "olvidé mi contraseña" hasta que expiran.
-- (En este proyecto no se usa la funcionalidad de reset, pero la
--  migración de Laravel la crea igualmente.)
-- --------------------------------------------------------------------
CREATE TABLE password_reset_tokens (
    email      VARCHAR(255) NOT NULL,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP    NULL,
    PRIMARY KEY (email)
);

-- --------------------------------------------------------------------
-- Sesiones de usuario
-- Laravel serializa aquí la información de cada sesión activa cuando
-- el driver SESSION_DRIVER=database está configurado en el .env.
-- Los índices aceleran las búsquedas por usuario y por expiración.
-- --------------------------------------------------------------------
CREATE TABLE sessions (
    id            VARCHAR(255)    NOT NULL,
    user_id       BIGINT UNSIGNED NULL,
    ip_address    VARCHAR(45)     NULL,    -- Soporta IPv6 (hasta 39 chars) + margen
    user_agent    TEXT            NULL,
    payload       LONGTEXT        NOT NULL,
    last_activity INT             NOT NULL,
    PRIMARY KEY (id),
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

-- --------------------------------------------------------------------
-- Caché de la aplicación
-- Almacena resultados cacheados (consultas pesadas, configuraciones)
-- para no recalcularlos en cada request.
-- --------------------------------------------------------------------
CREATE TABLE cache (
    cache_key  VARCHAR(255) NOT NULL,
    value      MEDIUMTEXT   NOT NULL,
    expiration INT          NOT NULL,
    PRIMARY KEY (cache_key)
);

-- --------------------------------------------------------------------
-- Cola de trabajos en background
-- Los jobs (tareas asíncronas como envío de emails) se encolan aquí.
-- El proceso php artisan queue:listen los consume en orden.
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
);


-- ====================================================================
--  TABLAS DEL DOMINIO — Biblioteca de Alejandría
--  Aquí vive la lógica de negocio del sistema.
-- ====================================================================

-- --------------------------------------------------------------------
-- 1. USUARIOS DEL SISTEMA (book_store_users)
--
--    El nombre personalizado "book_store_users" (en lugar del
--    estándar "users" de Laravel) evita colisiones si esta BD
--    se reutiliza en un proyecto Laravel que ya tenga su propia
--    tabla "users".
--
--    Dos roles posibles:
--      • admin → acceso total al panel /admin: CRUD de libros,
--                autores, categorías, préstamos y usuarios
--      • user  → acceso al catálogo /catalogo y a su perfil /perfil
--
--    El campo is_active permite "congelar" una cuenta sin eliminarla.
--    El middleware CheckRole detecta is_active = 0 en cada request
--    y desconecta al usuario automáticamente, redirigiendo al login.
--
--    birth_date es opcional pero importante: cuando un libro tiene
--    min_age > 0, el sistema calcula si el usuario cumple esa edad
--    mínima. Si birth_date es NULL, la validación se omite (no bloquea).
-- --------------------------------------------------------------------
CREATE TABLE book_store_users (
    id             BIGINT UNSIGNED      NOT NULL AUTO_INCREMENT,
    first_name     VARCHAR(255)         NOT NULL,
    last_name      VARCHAR(255)         NOT NULL,
    email          VARCHAR(255)         NOT NULL,
    password       VARCHAR(255)         NOT NULL,           -- Siempre hash bcrypt(cost≥10), NUNCA texto plano
    role           ENUM('admin','user') NOT NULL DEFAULT 'user',
    is_active      TINYINT(1)           NOT NULL DEFAULT 1, -- 1 = activo, 0 = bloqueado
    birth_date     DATE                 NULL,               -- Opcional: para restricción min_age en préstamos
    remember_token VARCHAR(100)         NULL,               -- Token "recuérdame" de Laravel
    created_at     TIMESTAMP            NULL,
    updated_at     TIMESTAMP            NULL,
    PRIMARY KEY (id),
    UNIQUE KEY book_store_users_email_unique (email),
    INDEX book_store_users_role_index (role),               -- Útil al listar solo admins o solo usuarios
    INDEX book_store_users_is_active_index (is_active)      -- Útil para alertas de usuarios inactivos en dashboard
);


-- --------------------------------------------------------------------
-- 2. CATEGORÍAS (categories)
--
--    La clasificación temática de nuestra biblioteca: Ciencia Ficción,
--    Historia, Derecho, Novela, Tecnología, etc.
--
--    Cada libro pertenece a UNA sola categoría (o a ninguna, si
--    category_id es NULL). La eliminación de una categoría no borra
--    los libros que la usan: su category_id simplemente queda en NULL
--    gracias a la política ON DELETE SET NULL en books.
-- --------------------------------------------------------------------
CREATE TABLE categories (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(255)    NOT NULL,
    description TEXT            NULL,    -- Descripción larga opcional para el catálogo
    created_at  TIMESTAMP       NULL,
    updated_at  TIMESTAMP       NULL,
    PRIMARY KEY (id)
);


-- --------------------------------------------------------------------
-- 3. AUTORES (authors)
--
--    Un libro puede ser escrito por múltiples autores y un autor
--    puede haber escrito múltiples libros → relación N:M
--    implementada por la tabla pivote book_author.
--
--    Gestión de la foto de perfil:
--      • Se guarda en storage/app/public/authors/ (disco "public")
--      • El accesor $author->photo_url devuelve la URL pública
--        completa para usar directamente en <img src="">
--      • Al ACTUALIZAR la foto → la foto anterior se elimina del disco
--        antes de guardar la nueva (sin archivos huérfanos)
--      • Al ELIMINAR el autor → la foto se elimina automáticamente
--        mediante el método destroy() del AuthorController
--
--    Formatos permitidos: jpeg, jpg, png  |  Tamaño máximo: 2 MB
-- --------------------------------------------------------------------
CREATE TABLE authors (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(255)    NOT NULL,
    last_name  VARCHAR(255)    NOT NULL,
    bio        TEXT            NULL,    -- Biografía completa, se muestra en la ficha del libro
    photo_path VARCHAR(255)    NULL,    -- Ruta relativa: "authors/foto_apellido.jpg"
    created_at TIMESTAMP       NULL,
    updated_at TIMESTAMP       NULL,
    PRIMARY KEY (id),
    INDEX authors_last_name_index (last_name)  -- Acelera filtrado por autor en el catálogo
);


-- --------------------------------------------------------------------
-- 4. LIBROS (books)
--
--    El corazón del inventario. Cada fila es un título único de la
--    biblioteca (no un ejemplar individual). Los ejemplares se
--    cuentan con stock_total y available_copies.
--
--    INVARIANTE DE STOCK — debe cumplirse siempre:
--      0 <= available_copies <= stock_total
--    El controlador BookController valida esto con la regla
--    'lte:stock_total' antes de guardar.
--
--    Campo "año": derivado automáticamente de published_at en
--    BookController al guardar. El formulario no envía este campo.
--    Permite filtrar/mostrar libros por año sin parsear fechas.
--
--    Campo "min_age": restricción de edad mínima en años para
--    poder solicitar el préstamo de este libro. 0 = sin restricción.
--    Ejemplos de uso: libros de contenido adulto (18), juveniles (12).
--
--    Campos eliminados en migración 2026_03_15_000001:
--      • codigo_interno → campo heredado que nunca tuvo uso real
--      • path_pdf       → los préstamos digitales se descartaron
--                         del alcance del proyecto
-- --------------------------------------------------------------------
CREATE TABLE books (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id      BIGINT UNSIGNED NULL,
    title            VARCHAR(255)    NOT NULL,
    isbn             VARCHAR(20)     NULL,    -- ISBN-10 o ISBN-13, único si se provee
    summary          TEXT            NULL,    -- Sinopsis o descripción del libro
    publisher        VARCHAR(150)    NULL,    -- Editorial: "Planeta", "Anagrama", etc.
    book_cover       VARCHAR(255)    NULL,    -- Ruta: "books/portada_titulo.jpg"
    published_at     DATE            NULL,    -- Fecha de publicación (día/mes/año)
    año              INT             NULL,    -- Año de publicación → derivado de published_at
    stock_total      INT UNSIGNED    NOT NULL DEFAULT 0,  -- Ejemplares físicos en la biblioteca
    available_copies INT UNSIGNED    NOT NULL DEFAULT 0,  -- Disponibles para préstamo ahora mismo
    min_age          INT UNSIGNED    NOT NULL DEFAULT 0,  -- Edad mínima (años). 0 = sin restricción
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    PRIMARY KEY (id),
    UNIQUE KEY books_isbn_unique (isbn),
    INDEX books_category_id_index (category_id),         -- Acelera filtrado del catálogo por categoría
    INDEX books_available_copies_index (available_copies), -- Acelera la búsqueda de libros con stock > 0
    INDEX books_min_age_index (min_age),                 -- Útil para filtrar libros sin restricción de edad
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE SET NULL    -- El libro permanece, pero sin categoría
        ON UPDATE CASCADE
);


-- --------------------------------------------------------------------
-- 5. TABLA PIVOTE: LIBROS ↔ AUTORES (book_author)
--
--    Este puente es el que hace posible que García Márquez pueda
--    aparecer en 10 libros y que "El Quijote" figure con Cervantes
--    como su único autor.
--
--    No tiene id propio (la clave primaria es compuesta: book_id +
--    author_id), ni timestamps, ya que es una tabla de relaciones pura.
--
--    Se gestiona en Laravel con:
--        $book->authors()->sync([1, 3, 7]);
--    Eso borra las asociaciones antiguas e inserta las nuevas en
--    una sola operación transaccional.
--
--    El CASCADE ON DELETE garantiza que si se elimina un libro o un
--    autor, los registros pivote correspondientes desaparezcan
--    automáticamente (no quedan filas huérfanas).
-- --------------------------------------------------------------------
CREATE TABLE book_author (
    book_id   BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (book_id, author_id),
    INDEX book_author_author_id_index (author_id),   -- Acelera "libros de este autor"
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
);


-- --------------------------------------------------------------------
-- 6. PRÉSTAMOS (loans)
--
--    La tabla más dinámica del sistema. Cada fila representa
--    un préstamo físico de un libro a un usuario, con su ciclo
--    de vida completo gestionado por estados (status).
--
--    ────────────────────────────────────────────────────────────
--    CICLO DE VIDA DE UN PRÉSTAMO
--    ────────────────────────────────────────────────────────────
--
--    [1] RESERVA ONLINE (Usuario hace clic en "Reservar este libro")
--          status    = 'pending'
--          qr_token  = UUID generado con Str::uuid()
--          loan_date = fecha de hoy (la reserva)
--          ⚡ En este momento: available_copies - 1
--             (el libro queda "apartado" para este usuario)
--
--    [2] VERIFICACIÓN PRESENCIAL (Usuario va a la biblioteca)
--          El usuario muestra el QR en pantalla o impreso.
--          El admin escanea el QR → abre el detalle del préstamo.
--
--    [3] ENTREGA FÍSICA (Admin confirma en el sistema)
--          status = 'active'
--          ✏️  Se registra en audit_logs
--          (el stock no cambia, ya bajó en el paso 1)
--
--    [4] DEVOLUCIÓN (Usuario regresa el libro)
--          status      = 'returned'
--          return_date = fecha de hoy (la devolución)
--          ⚡ En este momento: available_copies + 1
--          ✏️  Se registra en audit_logs
--          🔒  Protección anti-doble-devolución: si el préstamo ya
--              está 'returned', markReturned() no hace nada
--
--    [E] VENCIDO (Admin lo marca manualmente si no devuelven)
--          status = 'overdue'
--          El stock NO cambia (el libro sigue "fuera")
--
--    ────────────────────────────────────────────────────────────
--    GUARD ANTI-DUPLICADOS
--    Un usuario no puede tener dos reservas simultáneas del mismo
--    libro en estado 'pending' o 'active'.
--    Verificado en User\LoanController antes de crear la reserva.
-- --------------------------------------------------------------------
CREATE TABLE loans (
    id          BIGINT UNSIGNED                                   NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED                                   NOT NULL,
    book_id     BIGINT UNSIGNED                                   NOT NULL,
    status      ENUM('pending','active','returned','overdue')     NOT NULL DEFAULT 'pending',
    loan_date   DATE                                              NOT NULL,              -- Fecha de la reserva online
    return_date DATE                                              NULL,                  -- Se rellena al marcar devuelto
    qr_token    VARCHAR(255)                                      NULL,                  -- UUID para verificación presencial
    created_at  TIMESTAMP                                         NULL,
    updated_at  TIMESTAMP                                         NULL,
    PRIMARY KEY (id),
    UNIQUE KEY loans_qr_token_unique (qr_token),
    INDEX loans_user_id_status_index (user_id, status),  -- Guard anti-duplicados + "mis préstamos activos"
    INDEX loans_book_id_status_index (book_id, status),  -- Verificar disponibilidad de un título específico
    INDEX loans_status_index (status),                   -- Dashboard: contar activos, vencidos, etc.
    CONSTRAINT fk_loans_user
        FOREIGN KEY (user_id)
        REFERENCES book_store_users(id)
        ON DELETE CASCADE   -- Si se elimina el usuario, se eliminan sus préstamos
        ON UPDATE CASCADE,
    CONSTRAINT fk_loans_book
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON DELETE CASCADE   -- Si se elimina el libro, se eliminan sus préstamos
        ON UPDATE CASCADE
);


-- --------------------------------------------------------------------
-- 7. REGISTRO DE AUDITORÍA (audit_logs)
--
--    El guardián inmutable del sistema. Todo evento relevante queda
--    registrado aquí con quién, qué, cuándo y sobre qué objeto.
--
--    DISEÑO DELIBERADO SIN updated_at:
--    Los logs de auditoría son inmutables por principio de diseño.
--    Nunca deben modificarse después de su creación. Si no hay
--    updated_at, nadie puede actualizar un registro sin ser detectado.
--
--    ¿Qué genera registros aquí?
--      • BookObserver   → 'created' al registrar un libro nuevo
--      • BookObserver   → 'updated' al editar datos de un libro
--      • BookObserver   → 'deleted' al eliminar un libro del catálogo
--      • AuthController → 'login_failed' si alguien falla el login
--                         (guarda el email intentado + IP del cliente)
--      • LoanController → 'updated' al confirmar entrega (pending→active)
--      • LoanController → 'updated' al registrar devolución (→returned)
--
--    model_id puede ser NULL en eventos sin modelo asociado
--    (login_failed no tiene un objeto Eloquent detrás).
--
--    Los últimos 8 registros se muestran en el dashboard del admin.
-- --------------------------------------------------------------------
CREATE TABLE audit_logs (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED NULL,               -- NULL si el evento no tiene usuario autenticado
    action      VARCHAR(50)     NOT NULL,           -- 'created' | 'updated' | 'deleted' | 'login_failed'
    model_type  VARCHAR(100)    NOT NULL,           -- 'Book' | 'Loan' | 'Auth'
    model_id    BIGINT UNSIGNED NULL,               -- ID del objeto afectado (NULL en login_failed)
    description VARCHAR(255)    NOT NULL,           -- Texto legible: "Libro 'El Principito' creado"
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX audit_logs_created_at_index (created_at DESC), -- El dashboard consulta los últimos N registros
    INDEX audit_logs_action_index (action),              -- Útil para filtrar solo 'login_failed' o solo 'deleted'
    INDEX audit_logs_user_id_index (user_id),            -- Historial de acciones de un usuario específico
    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id)
        REFERENCES book_store_users(id)
        ON DELETE SET NULL  -- Si se elimina el usuario, el log se conserva (user_id queda NULL)
);


-- ====================================================================
--  RESUMEN DE REGLAS DE NEGOCIO
-- ====================================================================
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  STOCK FÍSICO                                                   │
--  │                                                                 │
--  │  stock_total      = copias físicas TOTALES de la biblioteca     │
--  │  available_copies = copias disponibles para préstamo AHORA      │
--  │                                                                 │
--  │  INVARIANTE: 0 <= available_copies <= stock_total               │
--  │                                                                 │
--  │  Al RESERVAR   (status → 'pending')  → available_copies - 1    │
--  │  Al DEVOLVER   (status → 'returned') → available_copies + 1    │
--  │  markReturned() protegido contra doble ejecución                │
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  FLUJO HÍBRIDO (Web + Presencial + QR)                          │
--  │                                                                 │
--  │  [web]        reserva online       →  status = 'pending'        │
--  │               stock --  en este paso                            │
--  │  [presencial] muestra QR en biblioteca                          │
--  │  [admin]      confirma entrega     →  status = 'active'         │
--  │  [presencial] devuelve libro                                    │
--  │  [admin]      marca devuelto       →  status = 'returned'       │
--  │               stock ++  en este paso                            │
--  │  [excepción]  no devuelve          →  status = 'overdue'        │
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  RESTRICCIÓN DE EDAD (min_age)                                  │
--  │                                                                 │
--  │  Si books.min_age > 0:                                          │
--  │    • Se calcula la edad del usuario desde birth_date            │
--  │    • Si edad < min_age → la reserva se rechaza con error        │
--  │    • Si birth_date es NULL → la validación se omite (no bloquea)│
--  └─────────────────────────────────────────────────────────────────┘
--
--  ┌─────────────────────────────────────────────────────────────────┐
--  │  GUARD ANTI-DUPLICADOS                                          │
--  │                                                                 │
--  │  Un usuario NO puede tener dos reservas en estado 'pending'     │
--  │  o 'active' del mismo libro simultáneamente.                    │
--  │  Verificado por User\LoanController@store antes de insertar.    │
--  └─────────────────────────────────────────────────────────────────┘
--
-- ====================================================================
--  DATOS DE PRUEBA
-- ====================================================================
--
--  ⚠️  Las contraseñas se hashean con bcrypt. NUNCA insertes texto plano.
--      Usa siempre: php artisan migrate:fresh --seed
--      Ese comando llama a DatabaseSeeder que usa Hash::make('password').
--
--  Usuarios de prueba generados por el seeder:
--
--    Email                   | Contraseña | Rol   | Estado
--    ────────────────────────┼────────────┼───────┼────────
--    admin@biblioteca.com    | password   | admin | activo
--    user@biblioteca.com     | password   | user  | activo
--
--  Para inserción manual de emergencia consulta:
--    GUIAS/Usuarios predeterminados del sistema.sql
--
-- ====================================================================
