<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/**
 * FETCH DATA
 */
$stmt = $pdo->query("SELECT * FROM fpk_misi_visi ORDER BY id ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * UPDATE DATA
 */
if (isset($_POST['update'])) {

    $stmt = $pdo->prepare("
        UPDATE fpk_misi_visi
        SET content = :content,
            updated_at = NOW()
        WHERE id = :id
    ");

    foreach ($_POST['content'] as $id => $content) {

        $stmt->execute([
            ':id' => $id,
            ':content' => $content
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

<title>Admin SMK S3 - FPK Sekolah</title>

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

/* ================= ALERT ================= */

.alert{
    border-radius:10px;
}

/* ================= FORM CARD ================= */

.form-cards{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
}

.form-cards .card{
    flex:1 1 calc(50% - 10px);
    border:none;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.card-header{
    background:#0d9488;
    color:white;
    text-align:center;
    font-weight:600;
    padding:14px;
}

.card-body{
    padding:15px;
}

.form-cards textarea{
    width:100%;
    border:1px solid #d1d5db;
    border-radius:8px;
    padding:12px;
    font-size:14px;
    resize:vertical;
    min-height:180px;
}

/* ================= BUTTON ================= */

.save-btn{
    border:none;
    background:#0d9488;
    color:white;
    padding:12px 20px;
    border-radius:8px;
    margin-top:25px;
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

    .form-cards .card{
        flex:1 1 100%;
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
    
    <h2 class="mb-4">
        FPK, Misi & Visi Sekolah
    </h2>

    <?php if(isset($_GET['success'])): ?>

        <div class="alert alert-success">
            Maklumat berjaya dikemaskini.
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-cards">

            <?php foreach($rows as $row): ?>

                <div class="card">

                    <div class="card-header">
                        <?= htmlspecialchars($row['kategori']) ?>
                    </div>

                    <div class="card-body">

                        <textarea 
                            name="content[<?= $row['id'] ?>]"
                            rows="6"
                        ><?= htmlspecialchars($row['content'] ?? '') ?></textarea>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <button type="submit" name="update" class="save-btn">
            Simpan Perubahan
        </button>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>