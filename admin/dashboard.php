<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* --- Base --- */
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
}
a {text-decoration: none;}

/* --- Sidebar --- */
.sidebar {
    width: 230px;
    height: 100vh;
    background: #0d9488;
    position: fixed;
    color: white;
    padding-top: 20px;
    overflow-y: auto;
}
.sidebar h4 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
}
.menu-item {
    margin-bottom: 5px;
}
.menu-item a,
.menu-title {
    display: block;
    padding: 12px 15px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: 0.2s;
}
.menu-item a:hover,
.menu-title:hover {
    background: #115e59;
    color: white;
}

/* --- Submenu --- */
.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    padding-left: 10px;
}
.menu-item.active > .submenu {
    max-height: 1000px; /* auto-expand */
}
.submenu a {
    font-size: 14px;
    padding: 10px 15px;
    color: white;
    display: block;
    border-radius: 6px;
}
.submenu a:hover {
    background: #14b8a6;
    color: white;
}

/* Nested submenu */
.submenu .submenu {
    padding-left: 20px;
}

/* --- Main content --- */
.main-content {
    margin-left: 230px;
    padding: 30px;
}

/* --- Card box --- */
.card-box {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
    .main-content {
        margin-left: 0;
        padding: 15px;
    }
}
</style>
</head>

<body>

<div class="sidebar">
    <h4>Sistem Sekolah</h4>

    <div class="menu-item">
        <a href="dashboard.php">Dashboard</a>
    </div>

    <div class="menu-item">
        <div class="menu-title" onclick="toggleMenu(this)">Manage Page ⯈</div>
        <div class="submenu">
            <!-- Nested submenu -->
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Pengurusan dan Pentadbiran ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php">Profil Sekolah</a>
                    <a href="pages-misi-visi-sekolah.php">FPK</a>
                    <a href="pages-sejarah-sekolah.php">Sejarah Sekolah</a>
                    <a href="pages-senarai-sekolah.php">Senarai Pengetua</a>
                    <a href="pages-pelan-sekolah.php">Pelan Sekolah</a>
                    <a href="pages-lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                    <a href="pages-pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
                    <a href="crud.php">Barisan Guru Dan AKP</a>
                    <a href="pages-kalendar-akademik.php">Kalendar Akademik</a>
                    <a href="pages-cuti-perayaan.php">Cuti Perayaan</a>
                </div>
            </div>
            <!-- Boleh tambah submenu lain bawah Manage Page -->
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Kurikulum ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php">Profil Sekolah</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Hal Ehwal Murid ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php">Profil Sekolah</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Kokurikulum ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php">Profil Sekolah</a>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">PIBG ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php">Profil Sekolah</a>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-item"><a href="#">Data Murid</a></div>
    <div class="menu-item"><a href="register.php">Register New Admin</a></div>
    <div class="menu-item"><a href="logout.php">Logout</a></div>
</div>

<div class="main-content">
    <h3>Dashboard</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card-box text-center">
                <h5>Jumlah Murid</h5>
                <h2>644</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-box text-center">
                <h5>Jumlah Guru</h5>
                <h2>43</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-box text-center">
                <h5>Tingkatan Tertinggi</h5>
                <h2>5</h2>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle submenu
function toggleMenu(el) {
    el.parentElement.classList.toggle("active");
}
</script>
</body>
</html>