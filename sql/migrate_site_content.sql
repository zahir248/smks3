-- Homepage / portal editable content (run once)
USE iesbcomm_smks3;

CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY DEFAULT 1,
    school_name VARCHAR(255) NOT NULL DEFAULT 'SMK Seremban 3',
    tagline VARCHAR(255) DEFAULT NULL,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(100),
    about_summary TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO settings (id, school_name, tagline, address, phone, email, about_summary) VALUES
(1,
 'Sekolah Menengah Kebangsaan Seremban 3',
 '',
 'Jalan Seremban Tiga 3 25, Seremban 3, 70300 Seremban, Negeri Sembilan',
 '011-65732533',
 'nea4117@moe.edu.my',
 'Sekolah Menengah Kebangsaan Seremban 3 ialah sekolah menengah yang komited menyediakan pendidikan berkualiti.'
);

CREATE TABLE IF NOT EXISTS site_content (
    content_key VARCHAR(100) PRIMARY KEY,
    content_value TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
