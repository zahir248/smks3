<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/**
 * Ambil data profil sekolah
 */
$stmt = $pdo->query("SELECT * FROM profil_sekolah WHERE id = 1 LIMIT 1");
$school = $stmt->fetch(PDO::FETCH_ASSOC);

/**
 * Kalau tiada data
 */
if (!$school) {
    $school = [];
}

/**
 * SAVE / UPDATE
 */
if (isset($_POST['update'])) {

    $data = [
        ':nama_pengetua' => $_POST['nama_pengetua'] ?? '',
        ':bilangan_guru' => $_POST['bilangan_guru'] ?? '',
        ':bilangan_murid' => $_POST['bilangan_murid'] ?? '',
        ':keluasan_sekolah' => $_POST['keluasan_sekolah'] ?? '',
        ':sesi_persekolahan' => $_POST['sesi_persekolahan'] ?? '',
        ':tingkatan_tertinggi' => $_POST['tingkatan_tertinggi'] ?? '',
        ':alamat_sekolah' => $_POST['alamat_sekolah'] ?? '',
        ':kod_sekolah' => $_POST['kod_sekolah'] ?? '',
        ':lokasi' => $_POST['lokasi'] ?? '',
        ':daerah_pentadbiran' => $_POST['daerah_pentadbiran'] ?? '',
        ':gred_sekolah' => $_POST['gred_sekolah'] ?? '',
        ':pejabat_pendidikan_daerah' => $_POST['pejabat_pendidikan_daerah'] ?? '',
        ':jenis_bantuan' => $_POST['jenis_bantuan'] ?? '',
    ];

    /**
     * UPDATE
     */
    if ($school) {

        $sql = "UPDATE profil_sekolah SET
            nama_pengetua = :nama_pengetua,
            bilangan_guru = :bilangan_guru,
            bilangan_murid = :bilangan_murid,
            keluasan_sekolah = :keluasan_sekolah,
            sesi_persekolahan = :sesi_persekolahan,
            tingkatan_tertinggi = :tingkatan_tertinggi,
            alamat_sekolah = :alamat_sekolah,
            kod_sekolah = :kod_sekolah,
            lokasi = :lokasi,
            daerah_pentadbiran = :daerah_pentadbiran,
            gred_sekolah = :gred_sekolah,
            pejabat_pendidikan_daerah = :pejabat_pendidikan_daerah,
            jenis_bantuan = :jenis_bantuan
            WHERE id = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

    } else {

        /**
         * INSERT
         */
        $sql = "INSERT INTO profil_sekolah (
            id,
            nama_pengetua,
            bilangan_guru,
            bilangan_murid,
            keluasan_sekolah,
            sesi_persekolahan,
            tingkatan_tertinggi,
            alamat_sekolah,
            kod_sekolah,
            lokasi,
            daerah_pentadbiran,
            gred_sekolah,
            pejabat_pendidikan_daerah,
            jenis_bantuan
        ) VALUES (
            1,
            :nama_pengetua,
            :bilangan_guru,
            :bilangan_murid,
            :keluasan_sekolah,
            :sesi_persekolahan,
            :tingkatan_tertinggi,
            :alamat_sekolah,
            :kod_sekolah,
            :lokasi,
            :daerah_pentadbiran,
            :gred_sekolah,
            :pejabat_pendidikan_daerah,
            :jenis_bantuan
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
    }

    header("Location: profil_sekolah.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin SMK S3 - Profil Sekolah</title>

<link rel="icon" type="image/png" href="../images/favicon.ico">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    background:#f4f6f9;
    font-family:'Segoe UI', sans-serif;
}

/* ================= MAIN CONTENT ================= */

.main-content{
    padding:30px;
}

/* ================= ALERT ================= */

.alert{
    border-radius:10px;
}

/* ================= FORM CARD ================= */

.form-cards{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
}

.form-cards .card{
    flex:1 1 calc(33.333% - 14px);
    border:none;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.card-header{
    background:#0d9488;
    color:white;
    text-align:center;
    font-weight:600;
    padding:14px;
}

.card-body{
    padding:15px;
}

.form-cards input,
.form-cards textarea{
    width:100%;
    border:1px solid #d1d5db;
    border-radius:8px;
    padding:10px;
    font-size:14px;
}

.form-cards textarea{
    resize:vertical;
    min-height:120px;
}

/* ================= BUTTON ================= */

.save-btn{
    border:none;
    background:#0d9488;
    color:white;
    padding:12px 20px;
    border-radius:8px;
    margin-top:25px;
    font-size:15px;
    transition:0.2s;
}

.save-btn:hover{
    background:#115e59;
}

.back-btn{
    display:inline-block;
    margin-bottom:15px;
    text-decoration:none;
    color:#0d9488;
    font-weight:600;
    transition:0.2s;
}

.back-btn:hover{
    color:#115e59;
    transform:translateX(-3px);
}
/* ================= RESPONSIVE ================= */

@media(max-width:992px){

    .form-cards .card{
        flex:1 1 calc(50% - 10px);
    }
}

@media(max-width:576px){

    .form-cards .card{
        flex:1 1 100%;
    }
}

</style>
</head>

<body>

<?php include 'includes/header.php'; ?>

<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

    <a href="manage_category.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    
    <h2 class="mb-4">
        Profil Sekolah Menengah Kebangsaan Seremban 3
    </h2>

    <?php if(isset($_GET['success'])): ?>

        <div class="alert alert-success">
            Profil sekolah berjaya dikemaskini.
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-cards">

            <?php

            $fields = [

                'Nama Pengetua' => 'nama_pengetua',
                'Bilangan Guru' => 'bilangan_guru',
                'Bilangan Murid' => 'bilangan_murid',
                'Keluasan Sekolah' => 'keluasan_sekolah',
                'Sesi Persekolahan' => 'sesi_persekolahan',
                'Tingkatan Tertinggi' => 'tingkatan_tertinggi',
                'Alamat Sekolah' => 'alamat_sekolah',
                'Kod Sekolah' => 'kod_sekolah',
                'Lokasi' => 'lokasi',
                'Daerah Pentadbiran' => 'daerah_pentadbiran',
                'Gred Sekolah' => 'gred_sekolah',
                'Pejabat Pendidikan Daerah' => 'pejabat_pendidikan_daerah',
                'Jenis Bantuan' => 'jenis_bantuan'

            ];

            foreach($fields as $label => $name){

                echo '<div class="card">';

                echo '<div class="card-header">'.$label.'</div>';

                echo '<div class="card-body">';

                if($name == 'alamat_sekolah'){

                    echo '<textarea name="'.$name.'">'
                    .htmlspecialchars($school[$name] ?? '').
                    '</textarea>';

                } else {

                    echo '<input 
                        type="'.(strpos($name,'bilangan') !== false ? 'number' : 'text').'"
                        name="'.$name.'"
                        value="'.htmlspecialchars($school[$name] ?? '').'"
                    >';

                }

                echo '</div>';

                echo '</div>';
            }

            ?>

        </div>

        <button type="submit" name="update" class="save-btn">
            Simpan Perubahan
        </button>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>