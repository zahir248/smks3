<?php
session_start();

/* SUPERADMIN ONLY */
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin'){
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getConnection();

/* GET DATA */
$stmt = $pdo->query("SELECT * FROM pra_sekolah WHERE id = 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$gambarCarta = $data['gambar_carta'] ?? '';
$gambarGaleri = $data['gambar_galeri'] ?? '';

/* SAVE */
if(isset($_POST['save'])){

    $uploadDir = __DIR__ . '/../uploads/pra_sekolah/';

    /* CREATE FOLDER */
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0755, true);
    }

    /* KEEP OLD */
    $newCarta = $gambarCarta;
    $newGaleri = $gambarGaleri;

    /* ALLOWED */
    $allowed = ['jpg','jpeg','png','webp'];

    /* =========================
       UPLOAD CARTA
    ========================= */
    if(!empty($_FILES['gambar_carta']['name'])){

        $ext = strtolower(pathinfo($_FILES['gambar_carta']['name'], PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){

            $fileName = 'carta_' . time() . '.' . $ext;

            if(move_uploaded_file(
                $_FILES['gambar_carta']['tmp_name'],
                $uploadDir . $fileName
            )){
                $newCarta = $fileName;
            }
        }
    }

    /* =========================
       UPLOAD GALERI
    ========================= */
    if(!empty($_FILES['gambar_galeri']['name'])){

        $ext = strtolower(pathinfo($_FILES['gambar_galeri']['name'], PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){

            $fileName = 'galeri_' . time() . '.' . $ext;

            if(move_uploaded_file(
                $_FILES['gambar_galeri']['tmp_name'],
                $uploadDir . $fileName
            )){
                $newGaleri = $fileName;
            }
        }
    }

    /* UPDATE */
    $stmt = $pdo->prepare("
        UPDATE pra_sekolah
        SET gambar_carta = :gambar_carta,
            gambar_galeri = :gambar_galeri
        WHERE id = 1
    ");

    $stmt->execute([
        ':gambar_carta' => $newCarta,
        ':gambar_galeri' => $newGaleri
    ]);

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}

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

.preview-img{
    width:100%;
    max-width:500px;
    border-radius:10px;
    margin-top:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
</style>

<div class="container py-5">

    <a href="manage_kurikulum.php" class="back-btn">
        ← Kembali
    </a>

    <h2 class="fw-bold mb-4">Pra Sekolah</h2>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Berjaya dikemaskini.
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form method="POST" enctype="multipart/form-data">

                <!-- CARTA -->
                <div class="mb-5">

                    <h5 class="fw-bold mb-3">
                        Carta Organisasi
                    </h5>

                    <input type="file"
                           name="gambar_carta"
                           class="form-control"
                           accept="image/*">

                    <?php if(!empty($gambarCarta)): ?>
                        <img src="../uploads/pra_sekolah/<?= htmlspecialchars($gambarCarta) ?>"
                             class="preview-img">
                    <?php endif; ?>

                </div>

                <!-- GALERI -->
                <div class="mb-4">

                    <h5 class="fw-bold mb-3">
                        Galeri Murid
                    </h5>

                    <input type="file"
                           name="gambar_galeri"
                           class="form-control"
                           accept="image/*">

                    <?php if(!empty($gambarGaleri)): ?>
                        <img src="../uploads/pra_sekolah/<?= htmlspecialchars($gambarGaleri) ?>"
                             class="preview-img">
                    <?php endif; ?>

                </div>

                <button type="submit"
                        name="save"
                        class="btn btn-primary">
                    Simpan
                </button>

            </form>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>