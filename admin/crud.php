<?php
session_start();
include "config.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$upload_dir = "../uploads/";

// CREATE / ADD Guru
if(isset($_POST['add_guru'])){

    $nama = $_POST['nama'];
    $jawatan = $_POST['jawatan'];
    $dg = $_POST['dg'];

    $image = time() . "_" . $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $image_path = $upload_dir . $image;

    if(move_uploaded_file($tmp_name, $image_path)){

        $sql = "INSERT INTO guru (nama, jawatan, dg, image) 
                VALUES ('$nama','$jawatan','$dg','$image')";

        if(mysqli_query($conn, $sql)){
            $_SESSION['message'] = "Guru berjaya ditambah!";
            header("Location: crud.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {
        echo "Gagal upload gambar.";
    }
}


// DELETE Guru
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $res = mysqli_query($conn,"SELECT image FROM guru WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    if($row && file_exists($upload_dir.$row['image'])){
        unlink($upload_dir.$row['image']);
    }

    $sql = "DELETE FROM guru WHERE id=$id";

    if(mysqli_query($conn, $sql)){
        $_SESSION['message'] = "Guru berjaya dipadam!";
        header("Location: crud.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


// FETCH DATA
$result = mysqli_query($conn, "SELECT * FROM guru");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Guru</title>
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
    max-height: 1000px;
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

table img{
    width:80px;
    height:80px;
    object-fit:cover;
}
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
                    <a href="pages-senarai-sekolah.php">Senarai Pengetua</a>
                    <a href="pages-pelan-sekolah.php">Pelan Sekolah</a>
                    <a href="pages-lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                    <a href="pages-pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
                    <a href="crud.php" style="background:#115e59;">Barisan Guru Dan AKP</a>
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
<h3>CRUD Guru</h3>

<?php if(isset($_SESSION['message'])): ?>
<div class="alert alert-success">
<?php 
echo $_SESSION['message']; 
unset($_SESSION['message']);
?>
</div>
<?php endif; ?>

<!-- FORM -->
<div class="card-box mb-4">
<h5>Tambah Guru Baru</h5>

<form method="POST" enctype="multipart/form-data" class="row g-3">

<div class="col-md-6">
<input type="text" name="nama" class="form-control" placeholder="Nama Guru" required>
</div>

<div class="col-md-3">
<input type="text" name="jawatan" class="form-control" placeholder="Jawatan" required>
</div>

<div class="col-md-3">
<input type="text" name="dg" class="form-control" placeholder="DG (Contoh DG44)" required>
</div>

<div class="col-md-12">
<input type="file" name="image" class="form-control" required>
</div>

<div class="col-md-12">
<button type="submit" name="add_guru" class="btn btn-primary">
Tambah Guru
</button>
</div>

</form>
</div>

<!-- TABLE -->
<div class="card-box">
<h5>Senarai Guru</h5>
<table class="table table-bordered">
<thead>
<tr>
<th>ID</th>
<th>Nama</th>
<th>Jawatan</th>
<th>DG</th>
<th>Gambar</th>
<th>Tindakan</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['nama'] ?></td>
<td><?= $row['jawatan'] ?></td>
<td><?= $row['dg'] ?></td>
<td>
<img src="../uploads/<?= $row['image'] ?>" alt="<?= $row['nama'] ?>">
</td>
<td>
<a href="edit_guru.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="crud.php?delete=<?= $row['id'] ?>" 
onclick="return confirm('Padam guru ini?');"
class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>

<script>
function toggleMenu(el) {
    el.parentElement.classList.toggle("active");
}
</script>

</body>
</html>