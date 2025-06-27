-- Seeder default menu
INSERT INTO menus (menu_code, menu_name, url_token, parent_code, order_num, is_active)
VALUES
  ('DASH', 'Dashboard', 'a1b2c3d4e5', NULL, 1, 1),
  ('USR',  'Manajemen User', 'f6g7h8i9j0', NULL, 2, 1),
  ('SET',  'Pengaturan', 'k1l2m3n4o5', NULL, 3, 1);
