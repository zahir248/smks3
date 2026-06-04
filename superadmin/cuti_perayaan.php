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
        FROM cuti_perayaan 
        WHERE id=?
    ");

    $stmt->execute([$_GET['delete']]);

    $file = $stmt->fetchColumn();

    $path = __DIR__ . "/../uploads/cuti_perayaan/";

    if($file && file_exists($path.$file)){
        unlink($path.$file);
    }

    $stmt = $pdo->prepare("
        DELETE FROM cuti_perayaan 
        WHERE id=?
    ");

    $stmt->execute([$_GET['delete']]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* =========================
   UPLOAD PDF ONLY
========================= */
if(isset($_POST['save'])) {

    if(empty($_FILES['pdf']['name'])){
        die("Sila pilih file PDF");
    }

    // ERROR CHECK
    if($_FILES['pdf']['error'] !== 0){
        die("Upload error code: ".$_FILES['pdf']['error']);
    }

    // SIZE CHECK (SEBELUM UPLOAD)
    if($_FILES['pdf']['size'] > 5 * 1024 * 1024){
        die("File terlalu besar (max 5MB)");
    }

    $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));

    if($ext != 'pdf'){
        die("Hanya PDF dibenarkan");
    }

    $fileName = time().'_'.rand(1000,9999).'.pdf';

    $folder = __DIR__ . "/../uploads/cuti_perayaan/";

    if(!is_dir($folder)){
        mkdir($folder, 0775, true);
    }

    $destination = $folder . $fileName;

    if(!move_uploaded_file($_FILES['pdf']['tmp_name'], $destination)){
        die("Upload gagal (move_uploaded_file fail)");
    }

    $stmt = $pdo->prepare("
        INSERT INTO cuti_perayaan (file_pdf)
        VALUES (:file_pdf)
    ");

    $stmt->execute([
        ':file_pdf' => $fileName
    ]);

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}

/* =========================
   FETCH DATA
========================= */
$stmt = $pdo->query("
    SELECT * 
    FROM cuti_perayaan 
    ORDER BY id DESC
");

$data = $stmt->fetchAll();

$settings = getSettings();

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

    <a href="manage_category.php" class="back-btn">
        ← Kembali
    </a>

    <h3 class="mb-4">
        Upload Cuti Perayaan (PDF)
    </h3>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">Upload berjaya</div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card p-4 mb-4 shadow-sm">

        <form method="POST" enctype="multipart/form-data">

            <label class="mb-2">
                Upload PDF sahaja
            </label>

            <input 
                type="file" 
                name="pdf" 
                class="form-control mb-3" 
                accept="application/pdf" 
                required
            >

            <button class="btn btn-primary" name="save">
                Upload
            </button>

        </form>

    </div>

    <!-- LIST -->
    <?php foreach($data as $row): ?>

        <div class="card p-3 mb-2 shadow-sm">

            <?php if(!empty($row['file_pdf'])): ?>

                <a href="../uploads/cuti_perayaan/<?= htmlspecialchars($row['file_pdf']) ?>" 
                   target="_blank" 
                   class="btn btn-success btn-sm">
                    📄 Lihat PDF
                </a>

            <?php endif; ?>

            <a href="?delete=<?= $row['id'] ?>" 
               class="btn btn-danger btn-sm mt-2"
               onclick="return confirm('Padam file ini?')">
               Delete
            </a>

        </div>

    <?php endforeach; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>