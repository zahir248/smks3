<?php
session_start();
include "config.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$upload_dir = "uploads/";

// Ambil ID guru
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM guru WHERE id=$id");
    $guru = mysqli_fetch_assoc($result);
}

// UPDATE DATA
if(isset($_POST['update_guru'])){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jawatan = $_POST['jawatan'];
    $dg = $_POST['dg'];

    // Check jika upload gambar baru
    if($_FILES['image']['name'] != ""){
        
        $image = time() . "_" . $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $image_path = $upload_dir . $image;

        if(move_uploaded_file($tmp_name, $image_path)){

            // Padam gambar lama
            $old = $_POST['old_image'];
            if(file_exists($upload_dir.$old)){
                unlink($upload_dir.$old);
            }

            mysqli_query($conn, "UPDATE guru 
            SET nama='$nama', jawatan='$jawatan', dg='$dg', image='$image' 
            WHERE id=$id");

        }

    } else {

        mysqli_query($conn, "UPDATE guru 
        SET nama='$nama', jawatan='$jawatan', dg='$dg' 
        WHERE id=$id");

    }

    $_SESSION['message'] = "Guru berjaya dikemaskini!";
    header("Location: crud.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Guru</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:Segoe UI;background:#f4f6f9;}
.container{max-width:600px;margin-top:60px;}
img{width:120px;height:120px;object-fit:cover;}
</style>
</head>
<body>

<div class="container">
<div class="card p-4 shadow">

<h4>Edit Guru</h4>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $guru['id'] ?>">
<input type="hidden" name="old_image" value="<?= $guru['image'] ?>">

<div class="mb-3">
<label>Nama Guru</label>
<input type="text" name="nama" class="form-control" value="<?= $guru['nama'] ?>" required>
</div>

<div class="mb-3">
<label>Jawatan</label>
<input type="text" name="jawatan" class="form-control" value="<?= $guru['jawatan'] ?>" required>
</div>

<div class="mb-3">
<label>DG</label>
<input type="text" name="dg" class="form-control" value="<?= $guru['dg'] ?>" required>
</div>

<div class="mb-3">
<label>Gambar Sekarang</label><br>
<img src="../uploads/<?= $guru['image'] ?>">
</div>

<div class="mb-3">
<label>Tukar Gambar (Optional)</label>
<input type="file" name="image" class="form-control">
</div>

<button type="submit" name="update_guru" class="btn btn-success">
Update Guru
</button>

<a href="crud.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>

</body>
</html>