<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

/* =========================
   ADD NEW PENGETUA
========================= */
if (isset($_POST['add_new'])) {

    $photo = '';

    if (!empty($_FILES['new_photo']['name'])) {

        $uploadDir = '../uploads/pengetua/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($_FILES['new_photo']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_new.' . $ext;

        move_uploaded_file(
            $_FILES['new_photo']['tmp_name'],
            $uploadDir . $fileName
        );

        $photo = 'uploads/pengetua/' . $fileName;
    }

    $stmt = $pdo->prepare("
        INSERT INTO pengetua (name, start_year, end_year, photo)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['new_name'] ?? '',
        $_POST['new_start_year'] ?? '',
        $_POST['new_end_year'] ?? '',
        $photo
    ]);

    header("Location: ".$_SERVER['PHP_SELF']."?added=1");
    exit;
}


/* =========================
   DELETE
========================= */
if (isset($_GET['delete_id'])) {

    $stmt = $pdo->prepare("SELECT photo FROM pengetua WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $photo = $stmt->fetchColumn();

    if (!empty($photo) && file_exists('../' . $photo)) {
        unlink('../' . $photo);
    }

    $stmt = $pdo->prepare("DELETE FROM pengetua WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);

    header("Location: ".$_SERVER['PHP_SELF']."?deleted=1");
    exit;
}


/* =========================
   UPDATE ALL
========================= */
if (isset($_POST['save_changes'])) {

    foreach ($_POST['id'] as $i => $id) {

        $stmt = $pdo->prepare("SELECT photo FROM pengetua WHERE id = ?");
        $stmt->execute([$id]);
        $currentPhoto = $stmt->fetchColumn();

        if (!empty($_FILES['photo']['name'][$i])) {

            $uploadDir = '../uploads/pengetua/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($_FILES['photo']['name'][$i], PATHINFO_EXTENSION);
            $fileName = time() . '_' . $id . '.' . $ext;

            move_uploaded_file(
                $_FILES['photo']['tmp_name'][$i],
                $uploadDir . $fileName
            );

            if (!empty($currentPhoto) && file_exists('../' . $currentPhoto)) {
                unlink('../' . $currentPhoto);
            }

            $currentPhoto = 'uploads/pengetua/' . $fileName;
        }

        $stmt = $pdo->prepare("
            UPDATE pengetua
            SET name = :name,
                start_year = :start_year,
                end_year = :end_year,
                photo = :photo
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':name' => $_POST['name'][$i] ?? '',
            ':start_year' => $_POST['start_year'][$i] ?? '',
            ':end_year' => $_POST['end_year'][$i] ?? '',
            ':photo' => $currentPhoto
        ]);
    }

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}


/* =========================
   FETCH DATA
========================= */
$stmt = $pdo->query("
    SELECT * 
    FROM pengetua 
    ORDER BY start_year ASC
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<!-- ================= MAIN CONTENT ================= -->
<div class="container py-4">

    <a href="manage_category.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>


<h2 class="mb-4 fw-bold">Manage Pengetua</h2>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">Data berjaya dikemaskini</div>
<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>
    <div class="alert alert-danger">Data berjaya dipadam</div>
<?php endif; ?>

<?php if(isset($_GET['added'])): ?>
    <div class="alert alert-success">Pengetua baru berjaya ditambah</div>
<?php endif; ?>


<!-- ================= ADD NEW ================= -->
<div class="card p-3 mb-4 shadow-sm">

<h5>Tambah Pengetua Baru</h5>

<form method="POST" enctype="multipart/form-data">

    <div class="row g-2">

        <div class="col-md-3">
            <input type="text" name="new_name" class="form-control" placeholder="Nama" required>
        </div>

        <div class="col-md-2">
            <input type="text" name="new_start_year" class="form-control" placeholder="Tahun Mula" required>
        </div>

        <div class="col-md-2">
            <input type="text" name="new_end_year" class="form-control" placeholder="Tahun Tamat">
        </div>

        <div class="col-md-3">
            <input type="file" name="new_photo" class="form-control">
        </div>

        <div class="col-md-2">
            <button type="submit" name="add_new" class="btn btn-primary w-100">
                Tambah
            </button>
        </div>

    </div>

</form>

</div>


<!-- ================= EDIT LIST ================= -->
<form method="POST" enctype="multipart/form-data">

<div class="card p-3 shadow-sm">

<?php foreach($data as $i => $row): ?>

<div class="border rounded p-3 mb-3">

    <input type="hidden" name="id[]" value="<?= $row['id'] ?>">

    <div class="row g-2 align-items-center">

        <div class="col-md-3">
            <input type="text" name="name[]" class="form-control"
                value="<?= htmlspecialchars($row['name']) ?>">
        </div>

        <div class="col-md-2">
            <input type="text" name="start_year[]" class="form-control"
                value="<?= htmlspecialchars($row['start_year']) ?>">
        </div>

        <div class="col-md-2">
            <input type="text" name="end_year[]" class="form-control"
                value="<?= htmlspecialchars($row['end_year']) ?>">
        </div>

        <div class="col-md-3">
            <?php if(!empty($row['photo'])): ?>
                <img src="../<?= $row['photo'] ?>"
                     style="width:60px;height:80px;object-fit:cover;border-radius:5px;">
            <?php endif; ?>

            <input type="file" name="photo[]" class="form-control mt-2">
        </div>

        <div class="col-md-2 text-end">
            <a href="?delete_id=<?= $row['id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Padam data ini?')">
               Delete
            </a>
        </div>

    </div>

</div>

<?php endforeach; ?>

<button type="submit" name="save_changes" class="btn btn-success">
    Simpan Perubahan
</button>

</div>

</form>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>