-- Jadual guru (Barisan Guru AKP) — untuk pangkalan sedia ada
USE smks3;

CREATE TABLE IF NOT EXISTS guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    jawatan VARCHAR(255) NOT NULL,
    dg VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
