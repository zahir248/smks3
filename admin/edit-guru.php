<?php
session_start();
require_once '../config/database.php';

$pdo = getConnection();

/* GET DATA */
if(!isset($_GET['id'])) {
    die("ID tidak dijumpai");
}

$id = $_GET['id'];

/* FETCH DATA */
$stmt = $pdo->prepare("SELECT * FROM guru WHERE id = :id");
$stmt->execute([':id' => $id]);
$guru = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$guru){
    die("Data tidak wujud");
}

/* UPDATE DATA */
if(isset($_POST['update_guru'])) {

    $nama = $_POST['nama'];
    $jawatan = $_POST['jawatan'];
    $dg = $_POST['dg'];

    $image = $guru['image']; // default lama

    /* kalau user upload gambar baru */
    if(!empty($_FILES['image']['name'])) {

        $newImage = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];

        move_uploaded_file($tmp, "../uploads/" . $newImage);

        $image = $newImage;
    }

    $stmt = $pdo->prepare("
        UPDATE guru 
        SET nama = :nama,
            jawatan = :jawatan,
            dg = :dg,
            image = :image
        WHERE id = :id
    ");

    $stmt->execute([
        ':nama' => $nama,
        ':jawatan' => $jawatan,
        ':dg' => $dg,
        ':image' => $image,
        ':id' => $id
    ]);

    $_SESSION['message'] = "Guru berjaya dikemaskini!";
    header("Location: crud.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Guru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card p-4">

<h3>Edit Guru</h3>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label>Nama</label>
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
<img src="../uploads/<?= $guru['image'] ?>" width="120">
</div>

<div class="mb-3">
<label>Upload Gambar Baru (optional)</label>
<input type="file" name="image" class="form-control">
</div>

<button type="submit" name="update_guru" class="btn btn-primary">
Update
</button>

<a href="crud.php" class="btn btn-secondary">Back</a>

</form>

</div>
</div>

</body>
</html>