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
    $stmt = $pdo->prepare("DELETE FROM pengetua WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: pengetua.php");
    exit();
}

// Handle insert/update
if (isset($_POST['save_changes'])) {
    foreach ($_POST['id'] as $index => $id) {
        $name = $_POST['name'][$index];
        $start_year = $_POST['start_year'][$index];
        $end_year = $_POST['end_year'][$index];

        if ($id) {
            $stmt = $pdo->prepare("UPDATE pengetua SET name=?, start_year=?, end_year=? WHERE id=?");
            $stmt->execute([$name, $start_year, $end_year, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO pengetua (name, start_year, end_year) VALUES (?, ?, ?)");
            $stmt->execute([$name, $start_year, $end_year]);
        }
    }
    header("Location: pages-senarai-pengetua.php");
    exit();
}

// Fetch all pengetua
$stmt = $pdo->query("SELECT * FROM pengetua ORDER BY start_year ASC");
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Sejarah Sekolah</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
}
a {text-decoration: none;}

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
.sidebar h4 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
}
.menu-item { margin-bottom: 5px; }
.menu-item a,
.menu-title {
    display: block;
    padding: 12px 15px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
}
.menu-item a:hover,
.menu-title:hover {
    background: #115e59;
}
.submenu { max-height: 0; overflow: hidden; transition: 0.3s; padding-left: 10px; }
.menu-item.active > .submenu { max-height: 1000px; }
.submenu a { font-size: 14px; padding: 10px 15px; }
.submenu a:hover { background: #14b8a6; }

/* Main */
.main-content { margin-left: 230px; padding: 30px; }

.card-box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

textarea.form-control { resize: vertical; min-height: 100px; }
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
                    <a href="pages-senarai-pengetua.php" style="background:#115e59;">Senarai Pengetua</a>
                    <a href="pages-pelan-sekolah.php">Pelan Sekolah</a>
                    <a href="pages-lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                    <a href="pages-pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
                    <a href="crud.php">Barisan Guru Dan AKP</a>
                    <a href="pages-kalendar-akademik.php">Kalendar Akademik</a>
                    <a href="pages-cuti-perayaan.php">Cuti Perayaan</a>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Kurikulum ⯈</div>
                <div class="submenu">
                    <a href="#">Profil Sekolah</a>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Hal Ehwal Murid ⯈</div>
                <div class="submenu">
                    <a href="#">Profil Sekolah</a>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">Kokurikulum ⯈</div>
                <div class="submenu">
                    <a href="#">Profil Sekolah</a>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="toggleMenu(this)">PIBG ⯈</div>
                <div class="submenu">
                    <a href="#">Profil Sekolah</a>
                </div>
            </div>

        </div>
    </div>

    <div class="menu-item"><a href="#">Data Murid</a></div>
    <div class="menu-item"><a href="register.php">Register New Admin</a></div>
    <div class="menu-item"><a href="logout.php">Logout</a></div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
<h3>Manage Senarai Pengetua</h3>

<form method="POST">
<div class="card-box">

<?php foreach($data as $index => $row): ?>
    <div class="mb-4 p-3 border rounded">
        <input type="hidden" name="id[]" value="<?= $row['id'] ?>">
        <div class="row mb-2">
            <div class="col-md-4">
                <label>Nama Pengetua</label>
                <input type="text" name="name[]" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
            </div>
            <div class="col-md-4">
                <label>Mulai Tahun</label>
                <input type="text" name="start_year[]" class="form-control" value="<?= htmlspecialchars($row['start_year']) ?>" required>
            </div>
            <div class="col-md-4">
                <label>Tamat Tahun</label>
                <input type="text" name="end_year[]" class="form-control" value="<?= htmlspecialchars($row['end_year']) ?>" required>
            </div>
        </div>
        <a href="pengetua.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Pasti nak delete?')">Delete</a>
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