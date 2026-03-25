-- School Website Database Schema
-- Run this in MySQL to create the database and tables

-- Must match config/database.php DB_NAME (default: smks3)
CREATE DATABASE IF NOT EXISTS smks3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smks3;

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

-- Daily page views (footer statistik kunjungan)
CREATE TABLE IF NOT EXISTS site_visit_daily (
    visit_date DATE NOT NULL PRIMARY KEY,
    visit_count INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Admin panel (admin/login.php, admin/register.php) — password = password_hash() bcrypt
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Guru / AKP photos (admin/crud.php, admin/edit_guru.php, client guru-apk.php) — files in /uploads/
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

-- Insert default settings
INSERT INTO settings (id, school_name, tagline, address, phone, email, about_summary) VALUES
(1, 'SMK S3', NULL, 'Jl. Pendidikan No. 1, Kota Kita', '(021) 12345678', 'info@smks3.sch.id', 'SMK S3 adalah sekolah menengah kejuruan yang berkomitmen memberikan pendidikan berkualitas dan siap kerja.');

-- Sample news (multi-paragraph HTML; dates control order on home / news listing)
INSERT INTO news (title, slug, excerpt, content, published_at) VALUES
('Kemasukan Pelajar Baru 2025', 'ppdb-2025', 'Pendaftaran kemasukan tahun persekolahan 2025/2026 dibuka bermula 1 April 2025.', '<p>Pendaftaran kemasukan pelajar baharu bagi sesi persekolahan 2025/2026 akan dibuka secara rasmi bermula 1 April 2025. Ibu bapa dan penjaga digalakkan membuat semakan dokumen asas awal supaya proses permohonan berjalan lancar.</p><p>Maklumat lengkap mengenai syarat kelayakan, borang, dan tarikh penting akan diumumkan melalui laman web sekolah, papan notis, serta media sosial rasmi. Sebarang pertanyaan boleh dibuat melalui pejabat sekolah pada waktu pejabat.</p>', '2025-02-10 09:00:00'),
('Aktiviti Latihan Industri', 'pkl-2025', 'Pelajar tingkatan lima menjalani latihan industri di pelbagai syarikat rakan.', '<p>Program latihan industri (PKL) dijalankan bagi tempoh tiga bulan untuk melengkapkan komponen vokasional pelajar tingkatan lima. Pelajar ditempatkan di syarikat rakan industri yang telah dipersetujui bersama pihak sekolah.</p><p>Semasa PKL, pelajar mempraktikkan kemahiran teknikal dan kerja berpasukan di persekitaran sebenar. Penilaian dan lawatan penyeliaan akan dijalankan bagi memastikan objektif pembelajaran tercapai.</p>', '2025-03-05 10:30:00'),
('Program Kokurikulum 2025', 'kokurikulum-2025', 'Pelbagai aktiviti kokurikulum dijadualkan untuk pembangunan holistik pelajar sepanjang tahun.', '<p>Sekolah merancang pelbagai aktiviti kokurikulum sepanjang tahun 2025 bagi memupuk kepimpinan, kerjasama, dan kesihatan mental serta fizikal pelajar. Kelab dan unit beruniform akan meneruskan sesi latihan dan pertandingan mengikut takwim.</p><p>Senarai aktiviti, tarikh, dan sebarang perubahan akan dikemas kini dari semasa ke semasa melalui papan notis sekolah dan saluran rasmi. Pelajar dinasihatkan merujuk guru penyelaras masing-masing untuk maklumat terperinci.</p>', '2025-04-18 14:00:00'),
('Lawatan Industri Pelajar Tingkatan Empat', 'lawatan-industri-tingkatan-empat-2025', 'Lawatan ke premis industri rakan kongsi bagi memperkenalkan persekitaran kerja sebenar kepada pelajar.', '<p>Pelajar tingkatan empat akan menjalani lawatan industri berpandu ke premis mitra yang telah dipersetujui. Objektif lawatan ialah menghubungkan pembelajaran di bilik darjah dengan amalan sebenar di industri.</p><p>Murid perlu mematuhi tatacara keselamatan dan pakaian yang ditetapkan. Sebarang perubahan tarikh atau lokasi akan dikemas kini oleh guru mata pelajaran berkaitan.</p>', '2025-05-01 08:30:00'),
('Hari Anugerah Cemerlang Akademik', 'hari-anugerah-cemerlang-2025', 'Majlis penghargaan bagi murid yang mencapai keputusan cemerlang dalam peperiksaan dan aktiviti akademik.', '<p>Majlis Anugerah Cemerlang akan meraikan pencapaian murid dalam akademik dan kompetensi berkaitan mengikut kriteria yang ditetapkan pihak sekolah. Majlis dijadualkan dengan susunan atur cara rasmi.</p><p>Senarai penerima anugerah dan panduan pakaian akan dimaklumkan kepada murid dan ibu bapa. Pihak sekolah mengucapkan tahniah kepada semua yang berjaya.</p>', '2025-05-15 14:00:00'),
('Kempen Keselamatan Siber Untuk Murid', 'kempen-keselamatan-siber-2025', 'Program kesedaran tentang penggunaan media sosial dan privasi dalam talian yang selamat.', '<p>Sekolah melaksanakan kempen keselamatan siber bagi membantu murid memahami risiko dalam talian, penipuan, dan perlindungan data peribadi. Sesi ceramah dan bahan rujukan akan disediakan.</p><p>Ibu bapa digalakkan berbincang dengan anak-anak tentang had masa skrin dan etika komunikasi digital. Rujukan tambahan boleh diperoleh dari unit kaunseling sekolah.</p>', '2025-06-01 11:30:00'),
('Mesyuarat Agung PIBG 2025', 'mesyuarat-agung-pibg-2025', 'Mesyuarat tahunan PIBG untuk membincangkan hal ehwal murid dan kerjasama ibu bapa dengan sekolah.', '<p>Mesyuarat Agung PIBG sesi 2025 akan membincangkan laporan tahunan, kewangan, dan aktiviti sokongan pendidikan murid. Kehadiran ibu bapa dan penjaga amat dialu-alukan.</p><p>Maklumat tarikh, masa, tempat, dan agenda rasmi akan dikeluarkan tidak lama lagi. Cadangan dan soalan boleh dikemukakan melalui jalan rasmi jawatankuasa PIBG.</p>', '2025-06-20 10:00:00'),
('Hari Sukan Sekolah 2025', 'hari-sukan-sekolah-2025', 'Sukan tahunan dijadualkan pada bulan Julai dengan pelbagai acara rumah sukan dan acara kecil.', '<p>Hari Sukan Sekolah 2025 akan diadakan dengan penyertaan semua murid melalui sistem rumah sukan. Acara trek dan padang serta permainan kecil akan dijalankan bagi memupuk semangat kerjasama dan kesihatan.</p><p>Jadual terperinci, kawalan keselamatan, dan tatacara penyertaan akan diumumkan melalui papan notis dan guru penyelaras sukan. Ibu bapa yang hadir digalakkan mematuhi arahan pihak sekolah.</p>', '2025-07-05 09:00:00');

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
