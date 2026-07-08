<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/**
 * FETCH CURRENT IMAGE
 */
$stmt = $pdo->query("
    SELECT * 
    FROM pelan_sekolah
    WHERE id = 1
    LIMIT 1
");

$data = $stmt->fetch(PDO::FETCH_ASSOC);

/**
 * DEFAULT VALUE
 */
$currentImage = $data['image'] ?? '';

/**
 * UPLOAD IMAGE
 */
if (isset($_POST['save'])) {

    $uploadDir = "../images/pelan-sekolah/";

    /**
     * Create folder if not exists
     */
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    /**
     * CHECK FILE
     */
    if (!empty($_FILES['image']['name'])) {

        $fileName = time() . '_' . basename($_FILES['image']['name']);

        $targetFile = $uploadDir . $fileName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($imageFileType, $allowed)) {

            /**
             * Upload file
             */
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {

                /**
                 * DELETE OLD IMAGE
                 */
                if (!empty($currentImage) && file_exists($uploadDir . $currentImage)) {
                    unlink($uploadDir . $currentImage);
                }

                /**
                 * UPDATE OR INSERT
                 */
                if ($data) {

                    $stmt = $pdo->prepare("
                        UPDATE pelan_sekolah
                        SET image = :image
                        WHERE id = 1
                    ");

                    $stmt->execute([
                        ':image' => $fileName
                    ]);

                } else {

                    $stmt = $pdo->prepare("
                        INSERT INTO pelan_sekolah (
                            id,
                            image
                        ) VALUES (
                            1,
                            :image
                        )
                    ");

                    $stmt->execute([
                        ':image' => $fileName
                    ]);
                }

                header("Location: ".$_SERVER['PHP_SELF']."?success=1");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin SMK S3 - Pelan Sekolah</title>

<link rel="icon" type="image/png" href="../images/favicon-smks3.ico">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>

body{
    margin:0;
    background:#f4f6f9;
    font-family:'Segoe UI', sans-serif;
}

/* ================= MAIN CONTENT ================= */

.main-content{
    padding:30px;
}

/* ================= BACK BUTTON ================= */

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

/* ================= PAGE TITLE ================= */

.page-title{
    font-weight:700;
    margin-bottom:25px;
}

/* ================= CARD ================= */

.card-box{
    background:white;
    border-radius:14px;
    padding:25px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
}

/* ================= IMAGE PREVIEW ================= */

.preview-image{
    width:100%;
    max-height:600px;
    object-fit:contain;
    border-radius:12px;
    border:1px solid #e5e7eb;
    margin-top:20px;
}

/* ================= BUTTON ================= */

.save-btn{
    border:none;
    background:#0d9488;
    color:white;
    padding:12px 20px;
    border-radius:8px;
    font-size:15px;
    transition:0.2s;
}

.save-btn:hover{
    background:#115e59;
}

/* ================= ALERT ================= */

.alert{
    border-radius:10px;
}

</style>

</head>

<body>

<?php include 'includes/header.php'; ?>

<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

    <a href="manage_category.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h2 class="page-title">
        Manage Pelan Sekolah
    </h2>

    <?php if(isset($_GET['success'])): ?>

        <div class="alert alert-success">
            Pelan sekolah berjaya dikemaskini.
        </div>

    <?php endif; ?>

    <div class="card-box">

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Upload Gambar Pelan Sekolah
                </label>

                <input 
                    type="file"
                    name="image"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

            </div>

            <button 
                type="submit"
                name="save"
                class="save-btn"
            >
                Simpan Gambar
            </button>

        </form>

        <?php if(!empty($currentImage)): ?>

            <img 
                src="../images/pelan-sekolah/<?= htmlspecialchars($currentImage) ?>"
                class="preview-image"
            >

        <?php endif; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>