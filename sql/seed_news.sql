-- Upsert sample berita (BM, berperenggan) into existing DB — same as config/database.php DB_NAME
USE smks3;

INSERT INTO news (title, slug, excerpt, content, published_at) VALUES
('Kemasukan Pelajar Baru 2025', 'ppdb-2025', 'Pendaftaran kemasukan tahun persekolahan 2025/2026 dibuka bermula 1 April 2025.', '<p>Pendaftaran kemasukan pelajar baharu bagi sesi persekolahan 2025/2026 akan dibuka secara rasmi bermula 1 April 2025. Ibu bapa dan penjaga digalakkan membuat semakan dokumen asas awal supaya proses permohonan berjalan lancar.</p><p>Maklumat lengkap mengenai syarat kelayakan, borang, dan tarikh penting akan diumumkan melalui laman web sekolah, papan notis, serta media sosial rasmi. Sebarang pertanyaan boleh dibuat melalui pejabat sekolah pada waktu pejabat.</p>', '2025-02-10 09:00:00'),
('Aktiviti Latihan Industri', 'pkl-2025', 'Pelajar tingkatan lima menjalani latihan industri di pelbagai syarikat rakan.', '<p>Program latihan industri (PKL) dijalankan bagi tempoh tiga bulan untuk melengkapkan komponen vokasional pelajar tingkatan lima. Pelajar ditempatkan di syarikat rakan industri yang telah dipersetujui bersama pihak sekolah.</p><p>Semasa PKL, pelajar mempraktikkan kemahiran teknikal dan kerja berpasukan di persekitaran sebenar. Penilaian dan lawatan penyeliaan akan dijalankan bagi memastikan objektif pembelajaran tercapai.</p>', '2025-03-05 10:30:00'),
('Program Kokurikulum 2025', 'kokurikulum-2025', 'Pelbagai aktiviti kokurikulum dijadualkan untuk pembangunan holistik pelajar sepanjang tahun.', '<p>Sekolah merancang pelbagai aktiviti kokurikulum sepanjang tahun 2025 bagi memupuk kepimpinan, kerjasama, dan kesihatan mental serta fizikal pelajar. Kelab dan unit beruniform akan meneruskan sesi latihan dan pertandingan mengikut takwim.</p><p>Senarai aktiviti, tarikh, dan sebarang perubahan akan dikemas kini dari semasa ke semasa melalui papan notis sekolah dan saluran rasmi. Pelajar dinasihatkan merujuk guru penyelaras masing-masing untuk maklumat terperinci.</p>', '2025-04-18 14:00:00')
ON DUPLICATE KEY UPDATE
  title = VALUES(title),
  excerpt = VALUES(excerpt),
  content = VALUES(content),
  published_at = VALUES(published_at);
