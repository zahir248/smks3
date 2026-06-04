<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getConnection();

/* =========================
   DELETE PROCESS
========================= */
if(isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("SELECT image FROM peraturan_sekolah WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row){

        $filePath = __DIR__ . '/../uploads/peraturan/' . $row['image'];

        if(file_exists($filePath)){
            unlink($filePath);
        }

        $stmt = $pdo->prepare("DELETE FROM peraturan_sekolah WHERE id=?");
        $stmt->execute([$id]);
    }

    header("Location: peraturan_upload.php");
    exit;
}

/* =========================
   UPLOAD PROCESS
========================= */
if(isset($_POST['submit'])) {

    if(empty($_FILES['image']['name'])){
        $error = "Sila pilih gambar";
    } else {

        $file = $_FILES['image'];

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            $error = "Format tidak dibenarkan";
        } else {

            $newName = time().'_'.rand(1000,9999).'.'.$ext;

            $uploadPath = __DIR__ . '/../uploads/peraturan/' . $newName;

            if(move_uploaded_file($file['tmp_name'], $uploadPath)){

                $stmt = $pdo->prepare("INSERT INTO peraturan_sekolah (image) VALUES (?)");
                $stmt->execute([$newName]);

                $success = "Upload berjaya!";
            } else {
                $error = "Upload gagal!";
            }
        }
    }
}

/* =========================
   GET ALL IMAGES
========================= */
$stmt = $pdo->query("SELECT * FROM peraturan_sekolah ORDER BY id DESC");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   HEADER
========================= */
$page_title = "Superadmin - Peraturan Sekolah";
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

        <h3 class="fw-bold mb-4">Manage Peraturan Sekolah (Upload Gambar)</h3>

        <!-- ALERT -->
        <?php if(!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- UPLOAD FORM -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <form method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Upload Gambar</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>

                    <button class="btn btn-primary" name="submit">
                        Upload
                    </button>

                </form>

            </div>
        </div>

        <!-- LIST IMAGE -->
        <div class="row">
            <?php foreach($images as $img): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">

                        <img 
                            src="../uploads/peraturan/<?= htmlspecialchars($img['image']) ?>" 
                            class="card-img-top"
                            style="height:250px; object-fit:contain; background:#f8f9fa;"
                        >

                        <div class="card-body text-center">

                            <a href="?delete=<?= $img['id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Padam gambar ini?')">
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