-- Seeder hak akses default
INSERT INTO access_rights (level, menu_code)
VALUES
  ('admin', 'DASH'),
  ('admin', 'USR'),
  ('admin', 'SET'),
  ('user',  'DASH');
