-- File: /app/Database/schema.sql

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    level VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel levels (opsional: untuk referensi level user)
CREATE TABLE IF NOT EXISTS levels (
    level VARCHAR(20) PRIMARY KEY,
    description VARCHAR(100) NOT NULL
);

-- Tabel login_logs (opsional: jika log ingin disimpan di DB bukan file)
CREATE TABLE IF NOT EXISTS login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    status ENUM('success', 'fail', 'blocked') NOT NULL,
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
