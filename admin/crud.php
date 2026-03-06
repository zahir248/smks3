<?php
session_start();
include "config.php"; // pastikan config.php ada $conn

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// Folder untuk simpan gambar
$upload_dir = "uploads/";

// CREATE / ADD Guru
if(isset($_POST['add_guru'])){
    $nama = $_POST['nama'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $image_path = $upload_dir . basename($image);

    if(move_uploaded_file($tmp_name, $image_path)){
        $sql = "INSERT INTO guru (nama, image) VALUES ('$nama','$image')";
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

    // Dapatkan nama gambar dulu untuk padam file
    $res = mysqli_query($conn,"SELECT image FROM guru WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    if($row && file_exists($upload_dir.$row['image'])){
        unlink($upload_dir.$row['image']); // padam gambar
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
body{font-family:Segoe UI;background:#f4f6f9;}
.sidebar{width:230px;height:100vh;background:#343a40;position:fixed;color:white;padding-top:20px;}
.sidebar h4{text-align:center;margin-bottom:30px;}
.sidebar a{display:block;padding:12px 20px;color:#ddd;text-decoration:none;}
.sidebar a:hover{background:#495057;color:white;}
.main-content{margin-left:230px;padding:30px;}
.card-box{background:white;padding:25px;border-radius:8px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
table img{width:80px;height:80px;object-fit:cover;}
</style>
</head>
<body>

<div class="sidebar">
<h4>Sistem Sekolah</h4>
<a href="dashboard.php">Dashboard</a>
<a href="crud.php">CRUD Guru</a>
<a href="#">Data Murid</a>
<a href="#">Data Guru</a>
<a href="#">Pengumuman</a>
<a href="logout.php">Logout</a>
</div>

<div class="main-content">
<h3>CRUD Guru</h3>

<?php if(isset($_SESSION['message'])): ?>
<div class="alert alert-success"><?php 
    echo $_SESSION['message']; 
    unset($_SESSION['message']);
?></div>
<?php endif; ?>

<!-- Form Tambah Guru -->
<div class="card-box mb-4">
<h5>Tambah Guru Baru</h5>
<form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <input type="text" name="nama" class="form-control" placeholder="Nama Guru" required>
    </div>
    <div class="col-md-6">
        <input type="file" name="image" class="form-control" required>
    </div>
    <div class="col-md-12">
        <button type="submit" name="add_guru" class="btn btn-primary">Tambah Guru</button>
    </div>
</form>
</div>

<!-- Papar Senarai Guru -->
<div class="card-box">
<h5>Senarai Guru</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Gambar</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><img src="uploads/<?= $row['image'] ?>" alt="<?= $row['nama'] ?>"></td>
            <td>
                <a href="edit_guru.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="crud.php?delete=<?= $row['id'] ?>" onclick="return confirm('Padam guru ini?');" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>