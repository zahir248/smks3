<?php
$page_title = 'Pengurusan Tertinggi Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <p class="text-center text-muted lead mb-4">Sesi 2026 — kenali barisan pengurusan tertinggi Sekolah Menengah Kebangsaan Seremban 3.</p>
        <style>
            .pengurusan-card {
                text-align: center;
                margin-bottom: 2rem;
                cursor: pointer;
            }
            .pengurusan-card .image-wrapper {
                width: 100%;
                padding-top: 100%; /* buat square petak penuh */
                position: relative;
                overflow: hidden;
            }
            .pengurusan-card img {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 100%;
                height: 100%;
                object-fit: contain; /* maintain ratio & muat penuh */
                transform: translate(-50%, -50%);
                transition: transform 0.3s, box-shadow 0.3s;
            }
            .pengurusan-card:hover img {
                transform: translate(-50%, -50%) scale(1.05);
                box-shadow: 0 8px 25px rgba(11,60,93,0.2);
            }
            .pengurusan-card h5 {
                margin-top: 0.5rem;
                transition: color 0.3s;
            }
            .pengurusan-card:hover h5 {
                color: #0B3C5D;
            }
        </style>

        <!-- Row 1 -->
        <div class="row justify-content-center">
            <div class="col-md-4 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/pengetua.png" alt="Pengetua">
                </div>
                <h5>SIAMALA A/P GOVINDAN</h5>
                <h5>DG14</h5>
                <h5>PENGETUA</h5>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row justify-content-center">
            <div class="col-md-4 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/pkhem.png" alt="PK HEM">
                </div>
                <h5>NOR ASHURA BINTI ZAINUDDIN</h5>
                <h5>DG14</h5>
                <h5>Penolong Kanan HEM</h5>
            </div>
            <div class="col-md-4 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/pkpentadbiran.png" alt="PK Pentadbiran">
                </div>
                <h5>AZIAH BINTI HASHIM</h5>
                <h5>DG12</h5>
                <h5>Penolong Kanan Pentadbiran</h5>
            </div>
            <div class="col-md-4 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/pkkokurikulum.png" alt="PK Kokurikulum">
                </div>
                <h5>AZRINA BINTI MOHAMED</h5>
                <h5>DG13</h5>
                <h5>Penolong Kanan Kokurikulum</h5>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row justify-content-center">
            <div class="col-md-3 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/guru1.png" alt="Guru 1">
                </div>
                <h5>HASNIZA BINTI HAZEMI</h5>
                <h5>DG12</h5>
                <h5>GKMP Sains & Matematik</h5>
            </div>
            <div class="col-md-3 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/guru2.png" alt="Guru 2">
                </div>
                <h5>NOR SAYEEDA SYAZANA BINTI BAKHTIAR</h5>
                <h5>DG12</h5>
                <h5>GKMP Bahasa</h5>
            </div>
            <div class="col-md-3 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/guru3.png" alt="Guru 3">
                </div>
                <h5>NORZILA BINTI IBRAHIM</h5>
                <h5>DG12</h5>
                <h5>GKMP Teknik & Vokasional</h5>
            </div>
            <div class="col-md-3 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/guru4.png" alt="Guru 4">
                </div>
                <h5>AB KARIM BIN PUTEH</h5>
                <h5>DG12</h5>
                <h5>GKMP Kemanusiaan</h5>
            </div>
        </div>

        <!-- Row 4 -->
        <div class="row justify-content-center">
            <div class="col-md-6 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/guru5.png" alt="Guru 5">
                </div>
                <h5>NURUL 'ADALAH BINTI ZULKAFLI</h5>
                <h5>DG9</h5>
                <h5>Kaunselor</h5>
            </div>
            <div class="col-md-6 pengurusan-card">
                <div class="image-wrapper">
                    <img src="images/guru6.png" alt="Guru 6">
                </div>
                <h5>DR ONG SING YEE</h5>
                <h5>DG12</h5>
                <h5>Kaunselor</h5>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>