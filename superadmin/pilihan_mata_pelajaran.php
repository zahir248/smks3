<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getConnection();
/* =========================
   DELETE PDF
========================= */
if(isset($_GET['delete'])) {

    $stmt = $pdo->prepare("
        SELECT file_pdf 
        FROM pilihan_mata_pelajaran 
        WHERE id=?
    ");
    $stmt->execute([$_GET['delete']]);
    $file = $stmt->fetchColumn();

    $path = __DIR__ . '/../uploads/pilihan_mata_pelajaran/';

    if($file && file_exists($path.$file)){
        unlink($path.$file);
    }

    $stmt = $pdo->prepare("
        DELETE FROM pilihan_mata_pelajaran 
        WHERE id=?
    ");
    $stmt->execute([$_GET['delete']]);

    header("Location: ".$_SERVER['PHP_SELF']."?deleted=1");
    exit;
}
/* =========================
   UPLOAD PDF
========================= */
if(isset($_POST['save'])) {

    if(empty($_FILES['pdf']['name'])){
        die("Sila pilih PDF");
    }

    if($_FILES['pdf']['error'] !== 0){
        die("Upload error");
    }

    if($_FILES['pdf']['size'] > 5 * 1024 * 1024){
        die("File terlalu besar (max 5MB)");
    }

    $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));

    if($ext !== 'pdf'){
        die("Hanya PDF sahaja dibenarkan");
    }

    $folder = __DIR__ . '/../uploads/pilihan_mata_pelajaran/';

    if(!is_dir($folder)){
        mkdir($folder, 0755, true);
    }

    $fileName = 'pmp_' . time() . '.pdf';

    if(!move_uploaded_file($_FILES['pdf']['tmp_name'], $folder.$fileName)){
        die("Upload gagal");
    }

    // OPTIONAL: replace old file (keep only 1 PDF)
    $pdo->query("DELETE FROM pilihan_mata_pelajaran");

    $stmt = $pdo->prepare("
        INSERT INTO pilihan_mata_pelajaran (file_pdf)
        VALUES (?)
    ");

    $stmt->execute([$fileName]);

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}

/* =========================
   GET CURRENT FILE
========================= */
$stmt = $pdo->query("SELECT * FROM pilihan_mata_pelajaran ORDER BY id DESC LIMIT 1");
$data = $stmt->fetch();

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

    <a href="manage_kurikulum.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h3 class="mb-4">Upload Pilihan Mata Pelajaran (PDF)</h3>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Berjaya dimuat naik
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['deleted'])): ?>
        <div class="alert alert-danger">
            PDF berjaya dipadam
        </div>
    <?php endif; ?>

    <!-- UPLOAD FORM -->
    <div class="card p-4 shadow-sm mb-4">

        <form method="POST" enctype="multipart/form-data">

            <input type="file" name="pdf" class="form-control mb-3" accept="application/pdf" required>

            <button class="btn btn-primary" name="save">
                Upload PDF
            </button>

        </form>

    </div>

    <!-- CURRENT FILE -->
    <div class="card p-3 shadow-sm">

        <h5 class="mb-3">PDF Semasa</h5>

        <?php if(!empty($data['file_pdf'])): ?>

            <a href="../uploads/pilihan_mata_pelajaran/<?= $data['file_pdf'] ?>"
               target="_blank"
               class="btn btn-success">

                📄 Lihat PDF
            </a>
            
            <!-- 🔴 DELETE BUTTON -->
            <a href="?delete=<?= $data['id'] ?>"
               onclick="return confirm('Padam PDF ini?')"
               class="btn btn-danger">
    
                🗑 Delete
            </a>

        <?php else: ?>
            <p class="text-muted">Belum ada PDF dimuat naik</p>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>