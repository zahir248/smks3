<?php
require_once 'includes/functions.php';
require_once 'config/database.php';
$pdo = getConnection(); // connection ke DB

// Ambil semua page (boleh filter ikut page_key nanti)
$pages = $pdo->query("SELECT * FROM pages WHERE page_key='kalendar_akademik'")->fetchAll();

// Handle update
if(isset($_POST['update'])){
    $stmt = $pdo->prepare("UPDATE pages SET title=?, content=? WHERE id=?");
    $stmt->execute([$_POST['title'], $_POST['content'], $_POST['id']]);
    header("Location: pages-kalendar-akademik.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin SMK S3 - Kalendar Akademik</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:Segoe UI; background:#f4f6f9; margin:0;}
.sidebar{width:230px; height:100vh; background:#0d9488; position:fixed; color:white; padding-top:20px; overflow-y:auto;}
.sidebar h4{text-align:center; margin-bottom:30px; font-weight:700;}
.menu-item {margin-bottom:5px;}
.menu-item a, .menu-title{display:block; padding:12px 15px; border-radius:8px; color:white; text-decoration:none; cursor:pointer; transition:0.2s;}
.menu-item a:hover, .menu-title:hover{background:#115e59; color:white;}
.submenu {max-height:0; overflow:hidden; transition: max-height 0.3s ease; padding-left:10px;}
.menu-item.active > .submenu {max-height:1000px;}
.submenu a{font-size:14px; padding:10px 15px; color:white; display:block; border-radius:6px;}
.submenu a:hover{background:#14b8a6; color:white;}
.submenu .submenu {padding-left:20px;}
.main-content{margin-left:230px; padding:30px;}
form button{padding:10px 20px; border:none; border-radius:6px; background:#0d9488; color:white; font-size:16px; cursor:pointer;}
form button:hover{background:#115e59;}
.form-cards{display:flex; flex-wrap:wrap; gap:20px;}
.form-cards .card{flex:1 1 calc(100%); background:white; border-radius:10px; padding:0; box-shadow:0 3px 10px rgba(0,0,0,0.1);}
.form-cards .card-header{background:#0d9488; color:white; padding:15px; font-weight:600; font-size:14px; text-align:center;}
.form-cards .card-body{padding:15px; display:flex; flex-direction:column; gap:10px;}
.form-cards textarea{width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size:14px;}
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
            <a href="pages-profil-sekolah.php">Profil Sekolah</a>
            <a href="pages-misi-visi-sekolah.php">FPK</a>
            <a href="pages-sejarah-sekolah.php">Sejarah Sekolah</a>
            <a href="pages-senarai-pengetua.php">Senarai Pengetua</a>
            <a href="pages-pelan-sekolah.php">Pelan Sekolah</a>
            <a href="pages-lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
            <a href="pages-pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
            <a href="crud.php">Barisan Guru & AKP</a>
            <a href="pages-kalendar-akademik.php" style="background:#115e59;">Kalendar Akademik</a>
            <a href="pages-cuti-perayaan.php">Cuti Perayaan</a>
        </div>
    </div>
    <div class="menu-item"><a href="register.php">Register New Admin</a></div>
    <div class="menu-item"><a href="logout.php">Logout</a></div>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2>Kalendar Akademik</h2>

    <div class="form-cards">
        <?php foreach($pages as $p): ?>
        <div class="card">
            <div class="card-header"><?= htmlspecialchars($p['title']) ?></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($p['title']) ?>" class="form-control mb-2">
                    <textarea name="content" class="form-control mb-2" rows="15"><?= htmlspecialchars($p['content']) ?></textarea>
                    <button name="update">Update</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function toggleMenu(el){ el.parentElement.classList.toggle("active"); }
</script>
</body>
</html>