-- 1. Creación de la base de datos
CREATE DATABASE IF NOT EXISTS biblioteca_alejandria;
USE biblioteca_alejandria;

-- 2. Tabla de Usuarios (book_store_users)
CREATE TABLE book_store_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 3. Tabla de Categorías
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 4. Tabla de Autores
CREATE TABLE authors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    bio TEXT,
    photo_path VARCHAR(255) NULL, -- Ruta de Storage::disk('public')
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 5. Tabla de Libros
CREATE TABLE books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED,
    title VARCHAR(255) NOT NULL,
    stock_total INT DEFAULT 0,
    available_copies INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 6. Tabla Pivot: Libros y Autores (Relación Muchos a Muchos)
CREATE TABLE book_author (
    book_id BIGINT UNSIGNED,
    author_id BIGINT UNSIGNED,
    PRIMARY KEY (book_id, author_id),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
);

-- 7. Tabla de Préstamos (Loans)
CREATE TABLE loans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    book_id BIGINT UNSIGNED,
    status ENUM('active', 'returned', 'overdue') DEFAULT 'active',
    loan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    return_date TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES book_store_users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- 8. Tabla de Auditoría (audit_logs) - Sin updated_at por requerimiento
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(50) NOT NULL, -- created, updated, deleted
    model_type VARCHAR(100) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES book_store_users(id) ON DELETE SET NULL
);