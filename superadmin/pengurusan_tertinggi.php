<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/**
 * DELETE
 */
if (isset($_GET['delete_id'])) {

    $stmt = $pdo->prepare("SELECT gambar FROM pengurusan WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists("../" . $img)) {
        unlink("../" . $img);
    }

    $stmt = $pdo->prepare("DELETE FROM pengurusan WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/**
 * UPDATE
 */
if (isset($_POST['save_changes'])) {

    $stmt = $pdo->prepare("
        UPDATE pengurusan
        SET nama = :nama,
            jawatan = :jawatan,
            gred = :gred,
            kategori = :kategori,
            gambar = :gambar
        WHERE id = :id
    ");

    foreach ($_POST['id'] as $i => $id) {

        $gambarPath = null;

        if (!empty($_FILES['gambar']['name'][$i])) {

            $fileName = time() . '_' . $_FILES['gambar']['name'][$i];
            $tmpName = $_FILES['gambar']['tmp_name'][$i];

            $uploadDir = "../uploads/pengurusan/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($tmpName, $uploadDir . $fileName);

            $gambarPath = "uploads/pengurusan/" . $fileName;

        } else {

            $stmt2 = $pdo->prepare("SELECT gambar FROM pengurusan WHERE id = ?");
            $stmt2->execute([$id]);
            $gambarPath = $stmt2->fetchColumn();
        }

        $stmt->execute([
            ':id' => $id,
            ':nama' => $_POST['nama'][$i],
            ':jawatan' => $_POST['jawatan'][$i],
            ':gred' => $_POST['gred'][$i],
            ':kategori' => $_POST['kategori'][$i],
            ':gambar' => $gambarPath
        ]);
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit;
}

/**
 * FETCH DATA
 */
$stmt = $pdo->query("SELECT * FROM pengurusan ORDER BY kategori ASC, id ASC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* HEADER */
$page_title = "Pengurusan Tertinggi";
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
<!-- MAIN CONTENT -->
<div class="container py-4">

    <a href="manage_category.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="d-flex align-items-center mb-3">
        <h3 class="mb-0">Admin Pengurusan Tertinggi</h3>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">Data berjaya dikemaskini.</div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <?php foreach($data as $row): ?>

            <div class="card mb-3 p-3 shadow-sm">

                <input type="hidden" name="id[]" value="<?= $row['id'] ?>">

                <div class="row g-2">

                    <div class="col-md-4">
                        <label>Nama</label>
                        <input type="text" name="nama[]" class="form-control"
                               value="<?= htmlspecialchars($row['nama']) ?>" required>
                    </div>

                    <div class="col-md-2">
                        <label>Gred</label>
                        <input type="text" name="gred[]" class="form-control"
                               value="<?= htmlspecialchars($row['gred']) ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label>Jawatan</label>
                        <input type="text" name="jawatan[]" class="form-control"
                               value="<?= htmlspecialchars($row['jawatan']) ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label>Kategori</label>
                        <select name="kategori[]" class="form-select">
                            <option value="pengetua" <?= $row['kategori']=='pengetua'?'selected':'' ?>>Pengetua</option>
                            <option value="pk" <?= $row['kategori']=='pk'?'selected':'' ?>>PK</option>
                            <option value="gkmp" <?= $row['kategori']=='gkmp'?'selected':'' ?>>GKMP</option>
                            <option value="kaunselor" <?= $row['kategori']=='kaunselor'?'selected':'' ?>>Kaunselor</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 d-flex align-items-center gap-3">
                    <input type="file" name="gambar[]">

                    <?php if($row['gambar']): ?>
                        <img src="../<?= htmlspecialchars($row['gambar']) ?>"
                             style="width:60px;height:60px;object-fit:cover;border-radius:50%;">
                    <?php endif; ?>
                </div>

                <div class="mt-3">
                    <a href="?delete_id=<?= $row['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Pasti nak delete?')">
                        Delete
                    </a>
                </div>

            </div>

        <?php endforeach; ?>

        <button type="submit" name="save_changes" class="btn btn-primary">
            Simpan Perubahan
        </button>

    </form>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>