<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/**
 * FETCH DATA
 */
$stmt = $pdo->query("
    SELECT * 
    FROM sejarah_sekolah 
    ORDER BY id ASC
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * UPDATE DATA
 */
if (isset($_POST['save_changes'])) {

    $stmt = $pdo->prepare("
        UPDATE sejarah_sekolah
        SET 
            tarikh = :tarikh,
            tajuk = :tajuk,
            content = :content
        WHERE id = :id
    ");

    foreach ($_POST['id'] as $i => $id) {

        $stmt->execute([
            ':id' => $id,
            ':tarikh' => $_POST['tarikh'][$i] ?? '',
            ':tajuk' => $_POST['tajuk'][$i] ?? '',
            ':content' => $_POST['content'][$i] ?? ''
        ]);
    }

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin SMK S3 - Sejarah Sekolah</title>

<link rel="icon" type="image/png" href="../images/favicon-smks3.ico">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

/* ================= TITLE ================= */

.page-title{
    margin-bottom:25px;
    font-weight:700;
}

/* ================= ALERT ================= */

.alert{
    border-radius:10px;
}

/* ================= CARD ================= */

.card-box{
    background:white;
    border-radius:14px;
    padding:25px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
}

/* ================= SEJARAH ITEM ================= */

.sejarah-item{
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:20px;
    margin-bottom:20px;
    background:#fafafa;
}

.sejarah-item label{
    font-weight:600;
    margin-bottom:6px;
    display:block;
}

.form-control{
    border-radius:8px;
    border:1px solid #d1d5db;
}

textarea.form-control{
    resize:vertical;
    min-height:140px;
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

/* ================= RESPONSIVE ================= */

@media(max-width:768px){

    .main-content{
        padding:20px;
    }

    .sejarah-item{
        padding:15px;
    }
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
        Manage Sejarah Sekolah
    </h2>

    <?php if(isset($_GET['success'])): ?>

        <div class="alert alert-success">
            Sejarah sekolah berjaya dikemaskini.
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="card-box">

            <?php foreach($data as $index => $row): ?>

                <div class="sejarah-item">

                    <!-- ROW TARIKH + TAJUK -->
                    <div class="row mb-3">

                        <div class="col-md-4">

                            <label>Tarikh</label>

                            <input 
                                type="hidden" 
                                name="id[]" 
                                value="<?= $row['id'] ?>"
                            >

                            <input 
                                type="text"
                                name="tarikh[]"
                                class="form-control"
                                value="<?= htmlspecialchars($row['tarikh'] ?? '') ?>"
                                required
                            >

                        </div>

                        <div class="col-md-8">

                            <label>Tajuk</label>

                            <input 
                                type="text"
                                name="tajuk[]"
                                class="form-control"
                                value="<?= htmlspecialchars($row['tajuk'] ?? '') ?>"
                                required
                            >

                        </div>

                    </div>

                    <!-- CONTENT -->
                    <div>

                        <label>Content</label>

                        <textarea
                            name="content[]"
                            class="form-control"
                            rows="6"
                            required
                        ><?= htmlspecialchars($row['content'] ?? '') ?></textarea>

                    </div>

                </div>

            <?php endforeach; ?>

            <button 
                type="submit"
                name="save_changes"
                class="save-btn"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>