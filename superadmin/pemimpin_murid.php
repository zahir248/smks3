<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getConnection();

/* =========================
   DELETE
========================= */
if(isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("SELECT image FROM pemimpin_murid WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row){
        $filePath = __DIR__ . '/../uploads/pemimpin_murid/' . $row['image'];
        if(file_exists($filePath)){
            unlink($filePath);
        }

        $stmt = $pdo->prepare("DELETE FROM pemimpin_murid WHERE id=?");
        $stmt->execute([$id]);
    }

    header("Location: pemimpin_murid_manage.php");
    exit;
}

/* =========================
   UPLOAD
========================= */
if(isset($_POST['submit'])) {

    $file = $_FILES['image'];

    if(empty($file['name'])){
        $error = "Sila pilih gambar";
    } else {

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            $error = "Format tidak dibenarkan";
        } else {

            $newName = time().'_'.rand(1000,9999).'.'.$ext;

            move_uploaded_file(
                $file['tmp_name'],
                __DIR__ . '/../uploads/pemimpin_murid/' . $newName
            );

            $stmt = $pdo->prepare("INSERT INTO pemimpin_murid (image) VALUES (?)");
            $stmt->execute([$newName]);

            $success = "Upload berjaya!";
        }
    }
}

/* =========================
   DATA LIST
========================= */
$stmt = $pdo->query("SELECT * FROM pemimpin_murid ORDER BY id DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   HEADER
========================= */
$page_title = "Superadmin - Pemimpin Murid";
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
<section class="py-5">
<div class="container">

    <a href="manage_hem.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h3 class="fw-bold mb-4">Manage Pemimpin Murid</h3>

    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- UPLOAD -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="image" class="form-control mb-3" required>
                <button class="btn btn-primary" name="submit">Upload</button>
            </form>
        </div>
    </div>

    <!-- LIST -->
    <div class="row">
        <?php foreach($data as $img): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <img src="../uploads/pemimpin_murid/<?= htmlspecialchars($img['image']) ?>"
                         class="card-img-top"
                         style="height:250px; object-fit:contain; background:#f8f9fa;">

                    <div class="card-body text-center">
                        <a href="?delete=<?= $img['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Padam gambar?')">
                           Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>