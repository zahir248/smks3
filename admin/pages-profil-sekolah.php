<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

require '../config/database.php';
$pdo = getConnection(); // ambil PDO connection

// Ambil data sekolah
$stmt = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1");
$school = $stmt->fetch();

// Handle update
if(isset($_POST['update'])){
    $stmt = $pdo->prepare("UPDATE profil_sekolah SET 
        nama_pengetua=:nama_pengetua,
        bilangan_guru=:bil_guru,
        bilangan_murid=:bil_murid,
        keluasan_sekolah=:keluasan,
        sesi_persekolahan=:sesi,
        tingkatan_tertinggi=:tingkatan,
        alamat_sekolah=:alamat,
        kod_sekolah=:kod,
        lokasi=:lokasi,
        daerah_pentadbiran=:daerah,
        gred_sekolah=:gred,
        pejabat_pendidikan_daerah=:ppd,
        jenis_bantuan=:bantuan
        WHERE id=:id
    ");

    $stmt->execute([
        ':nama_pengetua' => $_POST['nama_pengetua'],
        ':bil_guru' => (int)$_POST['bilangan_guru'],
        ':bil_murid' => (int)$_POST['bilangan_murid'],
        ':keluasan' => $_POST['keluasan_sekolah'],
        ':sesi' => $_POST['sesi_persekolahan'],
        ':tingkatan' => $_POST['tingkatan_tertinggi'],
        ':alamat' => $_POST['alamat_sekolah'],
        ':kod' => $_POST['kod_sekolah'],
        ':lokasi' => $_POST['lokasi'],
        ':daerah' => $_POST['daerah_pentadbiran'],
        ':gred' => $_POST['gred_sekolah'],
        ':ppd' => $_POST['pejabat_pendidikan_daerah'],
        ':bantuan' => $_POST['jenis_bantuan'],
        ':id' => $school['id']
    ]);

    echo "<script>alert('Profil Sekolah berjaya dikemaskini!'); window.location='pages-profil-sekolah.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin SMK S3 - Manage Profil Sekolah</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:Segoe UI; background:#f4f6f9; margin:0;}

/* --- Sidebar --- */
.sidebar{
    width:230px; height:100vh; background:#0d9488; position:fixed; color:white; padding-top:20px; overflow-y:auto;
}
.sidebar h4{text-align:center; margin-bottom:30px; font-weight:700;}
.menu-item {margin-bottom:5px;}
.menu-item a, .menu-title{
    display:block; padding:12px 15px; border-radius:8px; color:white; text-decoration:none; cursor:pointer; transition:0.2s;
}
.menu-item a:hover, .menu-title:hover{background:#115e59; color:white;}
.submenu {max-height:0; overflow:hidden; transition: max-height 0.3s ease; padding-left:10px;}
.menu-item.active > .submenu {max-height:1000px;}
.submenu a{font-size:14px; padding:10px 15px; color:white; display:block; border-radius:6px;}
.submenu a:hover{background:#14b8a6; color:white;}
.submenu .submenu {padding-left:20px;}

/* --- Main content --- */
.main-content{margin-left:230px; padding:30px;}
form button{padding:10px 20px; border:none; border-radius:6px; background:#0d9488; color:white; font-size:16px; cursor:pointer;}
form button:hover{background:#115e59;}

/* --- Card layout 3 columns --- */
.form-cards{display:flex; flex-wrap:wrap; gap:20px;}
.form-cards .card{
    flex:1 1 calc(33.333% - 13.33px);
    background:white; border-radius:10px; padding:0; box-shadow:0 3px 10px rgba(0,0,0,0.1); display:flex; flex-direction:column;
}
.form-cards .card-header{background:#0d9488; color:white; padding:15px; font-weight:600; font-size:14px; text-align:center;}
.form-cards .card-body{padding:15px; display:flex; flex-direction:column;}
.form-cards input, .form-cards textarea{width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size:14px;}
@media (max-width:992px){.form-cards .card{flex:1 1 calc(50% - 10px);}}
@media (max-width:576px){.form-cards .card{flex:1 1 100%;}}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Sistem Sekolah</h4>

    <div class="menu-item"><a href="dashboard.php">Dashboard</a></div>

    <div class="menu-item active">
        <div class="menu-title" onclick="toggleMenu(this)">Manage Page ⯈</div>
        <div class="submenu">
            <div class="menu-item active">
                <div class="menu-title" onclick="toggleMenu(this)">Pengurusan dan Pentadbiran ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php" style="background:#115e59;">Profil Sekolah</a>
                    <a href="pages-misi-visi-sekolah.php">FPK</a>
                    <a href="pages-sejarah-sekolah.php">Sejarah Sekolah</a>
                    <a href="pages-senarai-pengetua.php">Senarai Pengetua</a>
                    <a href="pages-pelan-sekolah.php">Pelan Sekolah</a>
                    <a href="pages-lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                    <a href="pages-pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
                    <a href="crud.php">Barisan Guru & AKP</a>
                    <a href="pages-kalendar-akademik.php">Kalendar Akademik</a>
                    <a href="pages-cuti-perayaan.php">Cuti Perayaan</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Kurikulum ⯈</div>
                <div class="submenu">
                    <a href="#">Profil Kurikulum</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Hal Ehwal Murid ⯈</div>
                <div class="submenu">
                    <a href="#">Profil Murid</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Kokurikulum ⯈</div>
                <div class="submenu">
                    <a href="#">Aktiviti Kokurikulum</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">PIBG ⯈</div>
                <div class="submenu">
                    <a href="#">Maklumat PIBG</a>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-item"><a href="#">Data Murid</a></div>
    <div class="menu-item"><a href="register.php">Register New Admin</a></div>
    <div class="menu-item"><a href="logout.php">Logout</a></div>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Profil Sekolah Menengah Kebangsaan Seremban 3</h2><br>
    <form method="POST">
        <div class="form-cards">
            <?php 
            $fields = [
                'Nama Pengetua'=>'nama_pengetua',
                'Bilangan Guru'=>'bilangan_guru',
                'Bilangan Murid'=>'bilangan_murid',
                'Keluasan Sekolah'=>'keluasan_sekolah',
                'Sesi Persekolahan'=>'sesi_persekolahan',
                'Tingkatan Tertinggi'=>'tingkatan_tertinggi',
                'Alamat Sekolah'=>'alamat_sekolah',
                'Kod Sekolah'=>'kod_sekolah',
                'Lokasi'=>'lokasi',
                'Daerah Pentadbiran'=>'daerah_pentadbiran',
                'Gred Sekolah'=>'gred_sekolah',
                'Pejabat Pendidikan Daerah'=>'pejabat_pendidikan_daerah',
                'Jenis Bantuan'=>'jenis_bantuan'
            ];

            foreach($fields as $label=>$name){
                echo '<div class="card">';
                echo '<div class="card-header">'.$label.'</div>';
                echo '<div class="card-body">';
                if($name==='alamat_sekolah'){
                    echo '<textarea name="'.$name.'">'.htmlspecialchars($school[$name]).'</textarea>';
                }else{
                    echo '<input type="'.(strpos($name,'bilangan')!==false?'number':'text').'" name="'.$name.'" value="'.htmlspecialchars($school[$name]).'">';
                }
                echo '</div></div>';
            }
            ?>
        </div>
        <button type="submit" name="update" class="mt-3">Simpan Perubahan</button>
    </form>
</div>

<script>
function toggleMenu(el){ el.parentElement.classList.toggle("active"); }
</script>
</body>
</html>