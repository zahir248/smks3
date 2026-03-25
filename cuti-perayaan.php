<?php
$page_title = 'Cuti Perayaan 2026';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero text-white py-5">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Cuti Perayaan Tahun 2026</h1>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">

        <!-- Kumpulan A & B di atas table -->
        <p class="lead text-start mb-4">
            <strong>Kumpulan A:</strong> Kedah, Kelantan, Terengganu<br>
            <strong>Kumpulan B:</strong> Johor, Melaka, Negeri Sembilan, Pahang, Perak, Perlis, Pulau Pinang, Sabah, Sarawak, Selangor, Wilayah Persekutuan KL, Labuan & Putrajaya
        </p>

        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cuti Perayaan</th>
                        <th>Kumpulan A</th>
                        <th>Kumpulan B</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Tahun Baru Cina -->
                    <tr>
                        <td rowspan="3">Tahun Baru Cina<br>17 & 18.02.2026</td>
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

                    <!-- Hari Raya Aidilfitri -->
                    <tr>
                        <td rowspan="2">Hari Raya Aidilfitri<br>21 & 22.03.2026</td>
                        <td>19.03.2026 (Khamis)</td>
                        <td>19.03.2026 (Khamis)</td>
                        <td rowspan="2">Satu (1) Hari Cuti Tambahan KPM untuk Kumpulan A dan Dua (2) Hari Cuti Tambahan KPM untuk Kumpulan B</td>
                    </tr>
                    <tr>
                        <td>21.03.2026 (Sabtu)</td>
                        <td>20.03.2026 (Jumaat)</td>
                    </tr>

                    <!-- Hari Deepavali -->
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

        <!-- Catatan -->
        <div class="mt-4">
            <h5>Catatan:</h5>
            <ul>
                <li>Hari Raya Aidilfitri: 21 & 22 Mac 2026 (Dalam Cuti Penggal 1, Tahun 2026)</li>
                <li>Pesta Kaamatan (Sabah & Wilayah Persekutuan Labuan sahaja): 30 & 31 Mei 2026 (Dalam Cuti Pertengahan Tahun 2026)</li>
                <li>Hari Gawai Dayak (Sarawak sahaja): 01 & 02 Jun 2026 (Dalam Cuti Pertengahan Tahun 2026)</li>
                <li>Hari Krismas: 25 Disember 2026 (Dalam Cuti Akhir Persekolahan Tahun 2026)</li>
            </ul>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>