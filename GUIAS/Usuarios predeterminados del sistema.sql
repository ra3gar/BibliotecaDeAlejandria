USE biblioteca_alejandria;

INSERT INTO book_store_users (first_name, last_name, email, password, role, is_active, created_at, updated_at)
  VALUES ('Admin', 'Sistema', 'admin@biblioteca.com',
  '$2y$12$Nk/9YEf8GzKgHnjB2VWv9uVCXwy9HI9pGYbCf66aKX.FccAxHTlMu', 'admin', 1, NOW(), NOW());

INSERT INTO book_store_users (first_name, last_name, email, password, role, is_active, created_at, updated_at)
  VALUES ('User', 'Sistema', 'user@biblioteca.com',
  '$2y$12$wKtYKt0DuVjNgEG88lqp1u/f5tHx3a1y.ZKP6Zpbqwnz0inx/R83y', 'user', 1, NOW(), NOW());