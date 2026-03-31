<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pdo = getConnection();

// Handle delete
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM pengurusan WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: admin-pengurusan-tertinggi.php");
    exit();
}

// Handle insert/update
if (isset($_POST['save_changes'])) {
    foreach ($_POST['id'] as $index => $id) {
        $nama = $_POST['nama'][$index];
        $gred = $_POST['gred'][$index];
        $jawatan = $_POST['jawatan'][$index];
        $kategori = $_POST['kategori'][$index];

        // Handle gambar upload
        $gambarPath = '';
        if (!empty($_FILES['gambar']['name'][$index])) {
            $uploadDir = __DIR__ . '/../images/';
            $gambarPath = 'images/' . basename($_FILES['gambar']['name'][$index]);
            move_uploaded_file($_FILES['gambar']['tmp_name'][$index], $uploadDir . basename($_FILES['gambar']['name'][$index]));
        }

        if ($id) {
            if($gambarPath != ''){
                $stmt = $pdo->prepare("UPDATE pengurusan SET nama=?, gred=?, jawatan=?, kategori=?, gambar=? WHERE id=?");
                $stmt->execute([$nama, $gred, $jawatan, $kategori, $gambarPath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE pengurusan SET nama=?, gred=?, jawatan=?, kategori=? WHERE id=?");
                $stmt->execute([$nama, $gred, $jawatan, $kategori, $id]);
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO pengurusan (nama, gred, jawatan, kategori, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $gred, $jawatan, $kategori, $gambarPath]);
        }
    }
    header("Location: admin-pengurusan-tertinggi.php");
    exit();
}

// Fetch all pengurusan
$stmt = $pdo->query("SELECT * FROM pengurusan ORDER BY susunan ASC");
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Pengurusan Tertinggi</title>
    <link rel="icon" type="image/png" href="../images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; margin:0; }
    a {text-decoration:none;}

    /* Sidebar */
    .sidebar {
        width: 230px;
        height: 100vh;
        background: #0d9488;
        position: fixed;
        color: white;
        padding-top: 20px;
        overflow-y: auto;
    }
    .sidebar h4 { text-align:center; margin-bottom:30px; font-weight:700; }
    .menu-item { margin-bottom:5px; }
    .menu-item a, .menu-title { display:block; padding:12px 15px; border-radius:8px; color:white; cursor:pointer; }
    .menu-item a:hover, .menu-title:hover { background:#115e59; }
    .submenu { max-height:0; overflow:hidden; transition:0.3s; padding-left:10px; }
    .menu-item.active > .submenu { max-height:1000px; }
    .submenu a { font-size:14px; padding:10px 15px; }
    .submenu a:hover { background:#14b8a6; }

    /* Main */
    .main-content { margin-left:230px; padding:30px; }
    .card-box { background:white; padding:25px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    input.form-control { margin-bottom:10px; }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Sistem Sekolah</h4>

    <div class="menu-item">
        <a href="dashboard.php">Dashboard</a>
    </div>

    <div class="menu-item active">
        <div class="menu-title" onclick="toggleMenu(this)">Manage Page ⯈</div>
        <div class="submenu">
            <div class="menu-item active">
                <div class="menu-title" onclick="toggleMenu(this)">Pengurusan dan Pentadbiran ⯈</div>
                <div class="submenu">
                    <a href="pages-profil-sekolah.php">Profil Sekolah</a>
                    <a href="pages-misi-visi-sekolah.php">FPK</a>
                    <a href="pages-sejarah-sekolah.php">Sejarah Sekolah</a>
                    <a href="pages-senarai-pengetua.php">Senarai Pengetua</a>
                    <a href="pages-pelan-sekolah.php">Pelan Sekolah</a>
                    <a href="pages-lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                    <a href="pages-pengurusan-tertinggi.php" style="background:#115e59;">Pengurusan Tertinggi Sekolah</a>
                    <a href="crud.php">Barisan Guru Dan AKP</a>
                    <a href="pages-kalendar-akademik.php">Kalendar Akademik</a>
                    <a href="pages-cuti-perayaan.php">Cuti Perayaan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-item"><a href="register.php">Register New Admin</a></div>
    <div class="menu-item"><a href="logout.php">Logout</a></div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
<h3>Admin Pengurusan Tertinggi</h3>

<form method="POST" enctype="multipart/form-data">
<div class="card-box">

<?php foreach($data as $index => $row): ?>
    <div class="mb-4 p-3 border rounded">
        <input type="hidden" name="id[]" value="<?= $row['id'] ?>">

        <div class="row mb-2">
            <div class="col-md-4">
                <label>Nama</label>
                <input type="text" name="nama[]" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
            </div>
            <div class="col-md-2">
                <label>Gred</label>
                <input type="text" name="gred[]" class="form-control" value="<?= htmlspecialchars($row['gred']) ?>" required>
            </div>
            <div class="col-md-3">
                <label>Jawatan</label>
                <input type="text" name="jawatan[]" class="form-control" value="<?= htmlspecialchars($row['jawatan']) ?>" required>
            </div>
            <div class="col-md-3">
                <label>Kategori</label>
                <select name="kategori[]" class="form-select" required>
                    <option value="pengetua" <?= $row['kategori']=='pengetua'?'selected':'' ?>>Pengetua</option>
                    <option value="pk" <?= $row['kategori']=='pk'?'selected':'' ?>>PK</option>
                    <option value="gkmp" <?= $row['kategori']=='gkmp'?'selected':'' ?>>GKMP</option>
                    <option value="kaunselor" <?= $row['kategori']=='kaunselor'?'selected':'' ?>>Kaunselor</option>
                </select>
            </div>
        </div>

        <div class="mb-2">
            <label>Gambar</label>
            <input type="file" name="gambar[]">
            <?php if($row['gambar']): ?>
                <img src="../<?= htmlspecialchars($row['gambar']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin-left:10px;">
            <?php endif; ?>
        </div>

        <a href="admin-pengurusan-tertinggi.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Pasti nak delete?')">Delete</a>
    </div>
<?php endforeach; ?>

<button type="submit" name="save_changes" class="btn btn-primary">Simpan Perubahan</button>
</div>
</form>

</div>

<script>
function toggleMenu(el) {
    el.parentElement.classList.toggle("active");
}
</script>

</body>
</html>