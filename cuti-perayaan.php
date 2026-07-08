<?php
$page_title = 'Cuti Perayaan 2026';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

$stmt = $pdo->query('
    SELECT *
    FROM cuti_perayaan
    ORDER BY id DESC
');
$cuti_pdfs = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<style>
.kumpulan-note {
    font-size: clamp(0.8rem, 1.2vw, 0.95rem);
    line-height: 1.6;
}

.jadual-cuti {
    font-size: clamp(0.75rem, 1vw, 0.9rem);
    background: #fff;
}

.jadual-cuti th,
.jadual-cuti td {
    padding: 8px;
}

.jadual-cuti thead th {
    white-space: nowrap;
}

.jadual-cuti tbody tr:nth-child(even) {
    background: #f8fafc;
}

.catatan-cuti {
    font-size: clamp(0.8rem, 1.1vw, 0.95rem);
    line-height: 1.6;
}

@media (max-width: 768px) {
    .jadual-cuti {
        font-size: 0.7rem;
    }

    .jadual-cuti th,
    .jadual-cuti td {
        padding: 6px;
    }
}

@media (max-width: 576px) {
    .jadual-cuti {
        font-size: 0.65rem;
    }
}
</style>

<section class="page-section">
    <div class="container">
        <p class="text-start mb-4 kumpulan-note">
            <strong>Kumpulan A:</strong> Kedah, Kelantan, Terengganu<br>
            <strong>Kumpulan B:</strong> Johor, Melaka, Negeri Sembilan, Pahang, Perak, Perlis, Pulau Pinang, Sabah, Sarawak, Selangor, Wilayah Persekutuan KL, Labuan &amp; Putrajaya
        </p>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered text-center align-middle jadual-cuti">
                <thead class="table-dark">
                    <tr>
                        <th>Cuti Perayaan</th>
                        <th>Kumpulan A</th>
                        <th>Kumpulan B</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3">Tahun Baru Cina<br>17 &amp; 18.02.2026</td>
                        <td>15.02.2026 (Ahad)</td>
                        <td>16.02.2026 (Isnin)</td>
                        <td rowspan="3">Tiga (3) Hari Cuti Tambahan KPM untuk Kumpulan A dan B</td>
                    </tr>
                    <tr>
                        <td>16.02.2026 (Isnin)</td>
                        <td>19.02.2026 (Khamis)</td>
                    </tr>
                    <tr>
                        <td>19.02.2026 (Khamis)</td>
                        <td>20.02.2026 (Jumaat)</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Hari Raya Aidilfitri<br>21 &amp; 22.03.2026</td>
                        <td>19.03.2026 (Khamis)</td>
                        <td>19.03.2026 (Khamis)</td>
                        <td rowspan="2">Satu (1) Hari Cuti Tambahan KPM untuk Kumpulan A dan Dua (2) Hari Cuti Tambahan KPM untuk Kumpulan B</td>
                    </tr>
                    <tr>
                        <td>21.03.2026 (Sabtu)</td>
                        <td>20.03.2026 (Jumaat)</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Hari Deepavali<br>08.11.2026 (Ahad) kecuali Negeri Sarawak</td>
                        <td>09.11.2026 (Isnin)</td>
                        <td>10.11.2026 (Selasa) Semua Negeri Kumpulan B kecuali Negeri Sarawak</td>
                        <td rowspan="2">Satu (1) Hari Cuti Tambahan KPM untuk Kumpulan A dan B / Satu (1) Hari Cuti Peruntukan KPM</td>
                    </tr>
                    <tr>
                        <td>09.11.2026 (Isnin)</td>
                        <td>09.11.2026 (Isnin) Negeri Sarawak sahaja</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 catatan-cuti">
            <h5>Catatan:</h5>
            <ul>
                <li>Hari Raya Aidilfitri: 21 &amp; 22 Mac 2026 (Dalam Cuti Penggal 1, Tahun 2026)</li>
                <li>Pesta Kaamatan (Sabah &amp; Wilayah Persekutuan Labuan sahaja): 30 &amp; 31 Mei 2026 (Dalam Cuti Pertengahan Tahun 2026)</li>
                <li>Hari Gawai Dayak (Sarawak sahaja): 01 &amp; 02 Jun 2026 (Dalam Cuti Pertengahan Tahun 2026)</li>
                <li>Hari Krismas: 25 Disember 2026 (Dalam Cuti Akhir Persekolahan Tahun 2026)</li>
            </ul>
        </div>

        <?php foreach ($cuti_pdfs as $row) :
            $file = $row['file_pdf'] ?? '';
            if ($file === '') {
                continue;
            }
            $filePath = 'uploads/cuti_perayaan/' . $file;
            if (!file_exists(__DIR__ . '/' . $filePath)) {
                continue;
            }
            $filePathEsc = htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8');
        ?>
        <div class="card shadow-sm border-0 mb-4 mt-4">
            <div class="card-body">
                <iframe
                    src="<?= $filePathEsc ?>"
                    title="Cuti Perayaan PDF"
                    width="100%"
                    height="700"
                    style="border:none; border-radius:8px;">
                </iframe>
                <div class="text-center mt-3">
                    <a href="<?= $filePathEsc ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Buka / Muat Turun PDF
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
