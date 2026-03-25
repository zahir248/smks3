<?php
$page_title = 'Kalendar Akademik 2026';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <p class="text-muted lead mb-4"><strong>Kumpulan B:</strong> Johor, Melaka, Negeri Sembilan, Pahang, Perak, Perlis, Pulau Pinang, Sabah, Sarawak, Selangor, Wilayah Persekutuan KL, Labuan & Putrajaya</p>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Penggal</th>
                        <th>Mula Persekolahan</th>
                        <th>Akhir Persekolahan</th>
                        <th>Jumlah Hari</th>
                        <th>Jumlah Minggu</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Penggal 1 -->
                    <tr>
                        <td rowspan="3">1</td>
                        <td>12.01.2026</td>
                        <td>31.01.2026</td>
                        <td>15</td>
                        <td rowspan="3">10</td>
                    </tr>
                    <tr>
                        <td>01.02.2026</td>
                        <td>28.02.2026</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>01.03.2026</td>
                        <td>20.03.2026</td>
                        <td>13</td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="3">Jumlah Hari</td>
                        <td>43</td>
                        <td>–</td>
                    </tr>

                    <!-- Cuti Penggal 1 -->
                    <tr>
                        <td colspan="5">Cuti Penggal 1, Tahun 2026</td>
                    </tr>
                    <tr>
                        <td>–</td>
                        <td>21.03.2026 - 29.03.2026</td>
                        <td>–</td>
                        <td>9</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>–</td>
                        <td>30.03.2026 - 31.03.2026</td>
                        <td>–</td>
                        <td>2</td>
                        <td>–</td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="3">Jumlah Hari</td>
                        <td>39</td>
                        <td>–</td>
                    </tr>

                    <!-- Penggal 2 -->
                    <tr>
                        <td rowspan="3">2</td>
                        <td>08.06.2026</td>
                        <td>30.06.2026</td>
                        <td>16</td>
                        <td rowspan="3">12</td>
                    </tr>
                    <tr>
                        <td>01.07.2026</td>
                        <td>31.07.2026</td>
                        <td>23</td>
                    </tr>
                    <tr>
                        <td>01.08.2026</td>
                        <td>28.08.2026</td>
                        <td>19</td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="3">Jumlah Hari</td>
                        <td>58</td>
                        <td>–</td>
                    </tr>

                    <!-- Cuti Penggal 2 -->
                    <tr>
                        <td colspan="5">Cuti Penggal 2, Tahun 2026</td>
                    </tr>
                    <tr>
                        <td>–</td>
                        <td>29.08.2026 - 06.09.2026</td>
                        <td>–</td>
                        <td>9</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>–</td>
                        <td>07.09.2026 - 30.09.2026</td>
                        <td>–</td>
                        <td>17</td>
                        <td>–</td>
                    </tr>

                    <!-- Cuti Akhir Tahun -->
                    <tr>
                        <td colspan="5">Cuti Akhir Persekolahan Tahun 2026</td>
                    </tr>
                    <tr>
                        <td>–</td>
                        <td>05.12.2026 - 31.12.2026</td>
                        <td>–</td>
                        <td>27</td>
                        <td>4</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>