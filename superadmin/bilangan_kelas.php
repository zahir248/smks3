<?php
session_start();

$page_title = 'Bilangan Kelas';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/upload_helper.php';

$pdo = getConnection();

/* =========================
   UPLOAD + INSERT
========================= */
if(isset($_POST['save'])) {

    $tingkatan = $_POST['tingkatan'] ?? '';
    $title = $_POST['title'] ?? '';

    if(!$tingkatan || !$title || empty($_FILES['image']['name'])){
        $_SESSION['error'] = "Sila lengkapkan semua data";
        header("Location: bilangan_kelas.php");
        exit;
    }

    $folder = __DIR__ . '/../uploads/bil_kelas/';

    $filename = uploadImage($_FILES['image'], $folder);

    if(!$filename){
        $_SESSION['error'] = "Upload gagal";
        header("Location: bilangan_kelas.php");
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO bilangan_kelas(tingkatan,title,image)
        VALUES(?,?,?)
    ");

    $stmt->execute([$tingkatan,$title,$filename]);

    $_SESSION['success'] = "Berjaya upload";
    header("Location: bilangan_kelas.php");
    exit;
}

/* =========================
   DELETE
========================= */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $q = $pdo->prepare("SELECT image FROM bilangan_kelas WHERE id=?");
    $q->execute([$id]);
    $img = $q->fetchColumn();

    if($img){
        @unlink(__DIR__ . '/../uploads/bil_kelas/'.$img);
    }

    $del = $pdo->prepare("DELETE FROM bilangan_kelas WHERE id=?");
    $del->execute([$id]);

    header("Location: bilangan_kelas.php");
    exit;
}

/* =========================
   DATA
========================= */
$data = $pdo->query("SELECT * FROM bilangan_kelas ORDER BY id DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<style>
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
</style>
<div class="container py-4">

<a href="manage_hem.php" class="back-btn">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<h2 class="fw-bold mb-3">Bilangan Kelas</h2>

<?php if(!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if(!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<!-- FORM -->
<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<select name="tingkatan" class="form-control mb-2" required>
    <option value="">Pilih Tingkatan</option>
    <option>TINGKATAN 1</option>
    <option>TINGKATAN 2</option>
    <option>TINGKATAN 3</option>
    <option>TINGKATAN 4</option>
    <option>TINGKATAN 5</option>
    <option>PRA</option>
</select>

<input type="text"
       name="title"
       class="form-control mb-2"
       placeholder="Contoh: 1 IKRAM"
       required>

<input type="file"
       name="image"
       class="form-control mb-3"
       required>

<button class="btn btn-primary w-100" name="save">
    Upload
</button>

</form>

</div>
</div>

<!-- LIST -->
<div class="card border-0 shadow-sm">
<div class="card-body">

<table class="table table-bordered align-middle">

<tr>
<th>Gambar</th>
<th>Tingkatan</th>
<th>Title</th>
<th>Aksi</th>
</tr>

<?php foreach($data as $d): ?>
<tr>

<td style="width:120px">
<img src="../uploads/bil_kelas/<?= $d['image'] ?>"
     style="width:100px;height:70px;object-fit:cover;">
</td>

<td><?= $d['tingkatan'] ?></td>
<td><?= $d['title'] ?></td>

<td>
<a href="?delete=<?= $d['id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Padam?')">
   Delete
</a>
</td>

</tr>
<?php endforeach; ?>

</table>

</div>
</div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>