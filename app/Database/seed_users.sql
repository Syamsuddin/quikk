-- Seeder default user
INSERT INTO users (username, password_hash, level)
VALUES
  ('admin', '$2y$10$eImiTXuWVxfM37uY4JANj.QzZcXaBd5yyWphGLkFad9Xuq5lqWdTy', 'admin'),
  ('user',  '$2y$10$uCjI9L7jDTNHoYHnLfE9feJkYv7aZQQaHJTCiZk8id0mSGIoN9uVS', 'user');
