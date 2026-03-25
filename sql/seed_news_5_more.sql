-- Tambah 5 berita baharu (slug baharu — selamat dijalankan berulang jika slug belum wujud)
USE smks3;

INSERT INTO news (title, slug, excerpt, content, published_at) VALUES
(
  'Hari Sukan Sekolah 2025',
  'hari-sukan-sekolah-2025',
  'Sukan tahunan dijadualkan pada bulan Julai dengan pelbagai acara rumah sukan dan acara kecil.',
  '<p>Hari Sukan Sekolah 2025 akan diadakan dengan penyertaan semua murid melalui sistem rumah sukan. Acara trek dan padang serta permainan kecil akan dijalankan bagi memupuk semangat kerjasama dan kesihatan.</p><p>Jadual terperinci, kawalan keselamatan, dan tatacara penyertaan akan diumumkan melalui papan notis dan guru penyelaras sukan. Ibu bapa yang hadir digalakkan mematuhi arahan pihak sekolah.</p>',
  '2025-07-05 09:00:00'
),
(
  'Mesyuarat Agung PIBG 2025',
  'mesyuarat-agung-pibg-2025',
  'Mesyuarat tahunan PIBG untuk membincangkan hal ehwal murid dan kerjasama ibu bapa dengan sekolah.',
  '<p>Mesyuarat Agung PIBG sesi 2025 akan membincangkan laporan tahunan, kewangan, dan aktiviti sokongan pendidikan murid. Kehadiran ibu bapa dan penjaga amat dialu-alukan.</p><p>Maklumat tarikh, masa, tempat, dan agenda rasmi akan dikeluarkan tidak lama lagi. Cadangan dan soalan boleh dikemukakan melalui jalan rasmi jawatankuasa PIBG.</p>',
  '2025-06-20 10:00:00'
),
(
  'Kempen Keselamatan Siber Untuk Murid',
  'kempen-keselamatan-siber-2025',
  'Program kesedaran tentang penggunaan media sosial dan privasi dalam talian yang selamat.',
  '<p>Sekolah melaksanakan kempen keselamatan siber bagi membantu murid memahami risiko dalam talian, penipuan, dan perlindungan data peribadi. Sesi ceramah dan bahan rujukan akan disediakan.</p><p>Ibu bapa digalakkan berbincang dengan anak-anak tentang had masa skrin dan etika komunikasi digital. Rujukan tambahan boleh diperoleh dari unit kaunseling sekolah.</p>',
  '2025-06-01 11:30:00'
),
(
  'Hari Anugerah Cemerlang Akademik',
  'hari-anugerah-cemerlang-2025',
  'Majlis penghargaan bagi murid yang mencapai keputusan cemerlang dalam peperiksaan dan aktiviti akademik.',
  '<p>Majlis Anugerah Cemerlang akan meraikan pencapaian murid dalam akademik dan kompetensi berkaitan mengikut kriteria yang ditetapkan pihak sekolah. Majlis dijadualkan dengan susunan atur cara rasmi.</p><p>Senarai penerima anugerah dan panduan pakaian akan dimaklumkan kepada murid dan ibu bapa. Pihak sekolah mengucapkan tahniah kepada semua yang berjaya.</p>',
  '2025-05-15 14:00:00'
),
(
  'Lawatan Industri Pelajar Tingkatan Empat',
  'lawatan-industri-tingkatan-empat-2025',
  'Lawatan ke premis industri rakan kongsi bagi memperkenalkan persekitaran kerja sebenar kepada pelajar.',
  '<p>Pelajar tingkatan empat akan menjalani lawatan industri berpandu ke premis mitra yang telah dipersetujui. Objektif lawatan ialah menghubungkan pembelajaran di bilik darjah dengan amalan sebenar di industri.</p><p>Murid perlu mematuhi tatacara keselamatan dan pakaian yang ditetapkan. Sebarang perubahan tarikh atau lokasi akan dikemas kini oleh guru mata pelajaran berkaitan.</p>',
  '2025-05-01 08:30:00'
);
