<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/* ===================== INSERT GURU ===================== */
if(isset($_POST['add_guru'])) {

    $nama = $_POST['nama'];
    $jawatan = $_POST['jawatan'];
    $dg = $_POST['dg'];

    $allowed = ['jpg','jpeg','png','gif'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $size = $_FILES['image']['size'];

    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        $_SESSION['message'] = "File tidak dibenarkan";
        header("Location: crud.php");
        exit;
    }

    if($size > 20 * 1024 * 1024){
        $_SESSION['message'] = "File terlalu besar (max 20MB)";
        header("Location: crud.php");
        exit;
    }

    $newName = time() . '_' . rand(1000,9999) . '.' . $ext;

    $uploadDir = __DIR__ . "/../uploads/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0755, true);
    }

    move_uploaded_file($tmp, $uploadDir . $newName);

    $stmt = $pdo->prepare("
        INSERT INTO guru (nama, jawatan, dg, image)
        VALUES (:nama, :jawatan, :dg, :image)
    ");

    $stmt->execute([
        ':nama' => $nama,
        ':jawatan' => $jawatan,
        ':dg' => $dg,
        ':image' => $newName
    ]);

    $_SESSION['message'] = "Guru berjaya ditambah!";
    header("Location: crud.php");
    exit;
}

/* ===================== INSERT AKP ===================== */
if(isset($_POST['add_akp'])) {

    $nama = $_POST['nama'];
    $jawatan = $_POST['jawatan'];
    $dg = $_POST['dg'];

    $allowed = ['jpg','jpeg','png','gif'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $size = $_FILES['image']['size'];

    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        $_SESSION['message'] = "File tidak dibenarkan";
        header("Location: crud.php");
        exit;
    }

    if($size > 20 * 1024 * 1024){
        $_SESSION['message'] = "File terlalu besar";
        header("Location: crud.php");
        exit;
    }

    $newName = time().'_'.rand(1000,9999).'.'.$ext;

    $uploadDir = __DIR__ . "/../uploads/";
    if(!move_uploaded_file($tmp, $uploadDir . $newName)){
        $_SESSION['message'] = "Upload gagal";
        header("Location: crud.php");
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO akp (nama, jawatan, dg, image)
        VALUES (:nama, :jawatan, :dg, :image)
    ");

    $stmt->execute([
        ':nama' => $nama,
        ':jawatan' => $jawatan,
        ':dg' => $dg,
        ':image' => $newName
    ]);

    $_SESSION['message'] = "AKP berjaya ditambah!";
    header("Location: crud.php");
    exit;
}

/* ===================== FETCH ===================== */
$guru = $pdo->query("SELECT * FROM guru ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$akp  = $pdo->query("SELECT * FROM akp ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

/* ===================== HEADER ===================== */
$page_title = "CRUD Guru & AKP";
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
    
    <!-- BACK BUTTON -->
    <a href="manage_category.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h3 class="mb-4">CRUD Guru & AKP</h3>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- FORM GURU -->
    <div class="card p-3 mb-4">
        <h5>Tambah Guru</h5>

        <form method="POST" enctype="multipart/form-data" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="nama" class="form-control" placeholder="Nama" required>
            </div>

            <div class="col-md-3">
                <input type="text" name="jawatan" class="form-control" placeholder="Jawatan" required>
            </div>

            <div class="col-md-3">
                <input type="text" name="dg" class="form-control" placeholder="DG44" required>
            </div>

            <div class="col-md-12">
                <input type="file" name="image" class="form-control" required>
            </div>

            <div class="col-md-12">
                <button class="btn btn-primary" name="add_guru">Tambah Guru</button>
            </div>
        </form>
    </div>

    <!-- FORM AKP -->
    <div class="card p-3 mb-4">
        <h5>Tambah AKP</h5>

        <form method="POST" enctype="multipart/form-data" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="nama" class="form-control" placeholder="Nama" required>
            </div>

            <div class="col-md-3">
                <input type="text" name="jawatan" class="form-control" placeholder="Jawatan" required>
            </div>

            <div class="col-md-3">
                <input type="text" name="dg" class="form-control" placeholder="DG44" required>
            </div>

            <div class="col-md-12">
                <input type="file" name="image" class="form-control" required>
            </div>

            <div class="col-md-12">
                <button class="btn btn-primary" name="add_akp">Tambah AKP</button>
            </div>
        </form>
    </div>

    <!-- TABLE GURU -->
    <div class="card p-3 mb-4">
        <h5>Senarai Guru</h5>

        <table class="table table-bordered">
            <tr>
                <th>Nama</th><th>Jawatan</th><th>DG</th><th>Gambar</th>
            </tr>

            <?php foreach($guru as $g): ?>
            <tr>
                <td><?= htmlspecialchars($g['nama']) ?></td>
                <td><?= htmlspecialchars($g['jawatan']) ?></td>
                <td><?= htmlspecialchars($g['dg']) ?></td>
                <td>
                    <img src="../uploads/<?= $g['image'] ?>" width="70">
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- TABLE AKP -->
    <div class="card p-3">
        <h5>Senarai AKP</h5>

        <table class="table table-bordered">
            <tr>
                <th>Nama</th><th>Jawatan</th><th>DG</th><th>Gambar</th>
            </tr>

            <?php foreach($akp as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['nama']) ?></td>
                <td><?= htmlspecialchars($a['jawatan']) ?></td>
                <td><?= htmlspecialchars($a['dg']) ?></td>
                <td>
                    <img src="../uploads/<?= $a['image'] ?>" width="70">
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>