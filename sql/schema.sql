-- School Website Database Schema
-- Run this in MySQL to create the database and tables

CREATE DATABASE IF NOT EXISTS school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_db;

-- School settings (single row)
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY DEFAULT 1,
    school_name VARCHAR(255) NOT NULL DEFAULT 'SMK S3',
    tagline VARCHAR(255) DEFAULT NULL,
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(100),
    about_summary TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (id = 1)
);

-- News / Announcements
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content TEXT,
    image_url VARCHAR(500) DEFAULT NULL,
    published_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses / Programs
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    duration VARCHAR(100) DEFAULT NULL,
    icon VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Staff / Teachers
CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    department VARCHAR(255) DEFAULT NULL,
    bio TEXT,
    photo_url VARCHAR(500) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages (from contact form)
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (id, school_name, tagline, address, phone, email, about_summary) VALUES
(1, 'SMK S3', 'Belajar, Berkreasi, Berprestasi', 'Jl. Pendidikan No. 1, Kota Kita', '(021) 12345678', 'info@smks3.sch.id', 'SMK S3 adalah sekolah menengah kejuruan yang berkomitmen memberikan pendidikan berkualitas dan siap kerja.');

-- Sample news
INSERT INTO news (title, slug, excerpt, content, published_at) VALUES
('Penerimaan Peserta Didik Baru 2025', 'ppdb-2025', 'Pendaftaran PPDB tahun ajaran 2025/2026 dibuka mulai 1 April 2025.', '<p>Informasi lengkap pendaftaran akan diumumkan melalui website dan media sosial sekolah.</p>', NOW()),
('Kegiatan Praktik Kerja Lapangan', 'pkl-2025', 'Siswa kelas XI melaksanakan PKL di berbagai industri mitra.', '<p>Program PKL dilaksanakan selama 3 bulan untuk mempersiapkan siswa memasuki dunia kerja.</p>', NOW());

-- Sample courses
INSERT INTO courses (name, slug, description, duration, icon, sort_order) VALUES
('Teknik Komputer dan Jaringan', 'tkj', 'Jurusan yang mempelajari jaringan komputer, server, dan sistem informasi.', '4 Tahun', 'bi-laptop', 1),
('Rekayasa Perangkat Lunak', 'rpl', 'Jurusan pemrograman dan pengembangan aplikasi serta software.', '4 Tahun', 'bi-code-slash', 2),
('Multimedia', 'multimedia', 'Jurusan desain grafis, video, animasi, dan konten digital.', '4 Tahun', 'bi-camera-video', 3);

-- Sample staff
INSERT INTO staff (name, role, department, bio, sort_order) VALUES
('Dr. Ahmad Wijaya, S.Pd., M.M.', 'Kepala Sekolah', 'Pimpinan', 'Memimpin SMK S3 dengan visi pendidikan vokasi yang unggul.', 1),
('Siti Nurhaliza, S.Kom.', 'Guru Produktif TKJ', 'Teknik Komputer dan Jaringan', 'Pengajar jaringan dan sistem komputer.', 2),
('Budi Santoso, S.T.', 'Guru Produktif RPL', 'Rekayasa Perangkat Lunak', 'Pengajar pemrograman dan pengembangan aplikasi.', 3);
