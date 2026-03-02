# SMK S3 - School Website

Website sekolah berbasis **PHP**, **MySQL**, dan **Bootstrap 5**.

## Fitur

- **Beranda** – Hero, pengenalan, jurusan, dan berita terbaru
- **Tentang** – Profil sekolah, visi & misi, kontak
- **Jurusan** – Daftar program keahlian (TKJ, RPL, Multimedia, dll.)
- **Guru & Staf** – Profil tenaga pendidik
- **Berita** – Daftar berita dan halaman detail
- **Kontak** – Form kontak yang menyimpan ke database

## Persyaratan

- PHP 7.4+ (dengan ekstensi PDO MySQL)
- MySQL 5.7+ atau MariaDB
- Web server (Apache/Nginx) atau PHP built-in server

## Instalasi

### 1. Clone atau salin proyek ke folder web Anda

### 2. Buat database MySQL

```bash
mysql -u root -p < sql/schema.sql
```

Atau jalankan isi file `sql/schema.sql` di phpMyAdmin / MySQL client.

### 3. Konfigurasi database

Edit `config/database.php` dan sesuaikan:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_db');
define('DB_USER', 'root');
define('DB_PASS', 'password_anda');
```

### 4. Jalankan website

**Laragon (disarankan):**

1. Pindahkan atau salin folder proyek ke `C:\laragon\www\smks3`
2. Di Laragon: **Start All** (Apache + MySQL)
3. Import database: buka **HeidiSQL** (Menu Laragon → MySQL → HeidiSQL) atau **phpMyAdmin** (`http://localhost/phpmyadmin`), buat database baru `school_db`, lalu jalankan isi file `sql/schema.sql`
4. Di Laragon default, MySQL user `root` tanpa password — di `config/database.php` biarkan `DB_PASS` kosong: `define('DB_PASS', '');`
5. Buka di browser: **http://smks3.test** (jika virtual host aktif) atau **http://localhost/smks3**

**PHP built-in server:**

```bash
cd smks3
php -S localhost:8000
```

Buka: http://localhost:8000

**XAMPP/WAMP:**  
Salin folder ke `htdocs` (XAMPP) atau `www` (WAMP), lalu akses via `http://localhost/smks3`

## Struktur Folder

```
smks3/
├── config/
│   └── database.php      # Koneksi database
├── includes/
│   ├── header.php        # Header & navigasi
│   ├── footer.php        # Footer
│   └── functions.php     # Helper & settings
├── sql/
│   └── schema.sql        # Skema & data awal
├── index.php             # Beranda
├── about.php             # Tentang
├── courses.php           # Jurusan
├── staff.php             # Guru & Staf
├── news.php              # Berita (list & detail)
├── contact.php           # Kontak + form
└── README.md
```

## Tanpa Database

Website tetap bisa dijalankan tanpa MySQL. Halaman akan menampilkan data default (fallback) dari kode. Untuk fitur penuh (berita dinamis, simpan pesan kontak), database harus diatur.

## Lisensi

Proyek ini dapat digunakan dan dimodifikasi untuk keperluan pendidikan.
