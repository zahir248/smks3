<?php
session_start();

$page_title = 'Manage Enrolment Murid';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getConnection();

require_once __DIR__ . '/includes/header.php';

/* =========================
   SAVE ENROLMENT
========================= */
if(isset($_POST['save'])) {

    $title = trim($_POST['title']);

    if(empty($title)){
        die("Title diperlukan");
    }

    if(empty($_FILES['image']['name'])){
        die("Sila pilih gambar");
    }

    $file = $_FILES['image'];

    if($file['error'] !== 0){
        die("Upload gagal");
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if(!in_array($ext, $allowed)){
        die("Format gambar tidak sah");
    }

    $newName = time() . '_' . rand(1000,9999) . '.' . $ext;

    $uploadPath = __DIR__ . '/../uploads/enrolmen/' . $newName;

    if(!move_uploaded_file($file['tmp_name'], $uploadPath)){
        die("Gagal upload gambar");
    }

    $stmt = $pdo->prepare("
        INSERT INTO enrolmen_murid(title, image)
        VALUES(?, ?)
    ");

    $stmt->execute([$title, $newName]);

    header("Location: enrolmen_murid.php?success=1");
    exit;
}

/* =========================
   DELETE
========================= */
if(isset($_GET['delete'])){

    $id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM enrolmen_murid
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $data = $stmt->fetch();

    if($data){

        $imagePath = __DIR__ . '/../uploads/enrolmen/' . $data['image'];

        if(file_exists($imagePath)){
            unlink($imagePath);
        }

        $delete = $pdo->prepare("
            DELETE FROM enrolmen_murid
            WHERE id = ?
        ");

        $delete->execute([$id]);
    }

    header("Location: enrolmen_murid.php");
    exit;
}

/* =========================
   GET DATA
========================= */
$rows = $pdo->query("
    SELECT *
    FROM enrolmen_murid
    ORDER BY id DESC
")->fetchAll();
?>

<style>
.preview-img{
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.card{
    border-radius: 12px;
}

.table td,
.table th{
    vertical-align: middle;
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
</style>

<div class="container py-4">
    
    <a href="manage_hem.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Manage Enrolment Murid
            </h2>

            <p class="text-muted mb-0">
                Upload gambar dan title enrolment murid.
            </p>
        </div>

    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Enrolment berjaya ditambah.
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Title
                    </label>

                    <input type="text"
                           name="title"
                           class="form-control"
                           placeholder="Contoh: ENROLMENT FEBRUARY"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Upload Gambar
                    </label>

                    <input type="file"
                           name="image"
                           class="form-control"
                           accept=".jpg,.jpeg,.png,.webp"
                           required>

                </div>

                <button type="submit"
                        name="save"
                        class="btn btn-primary">
                    Save
                </button>

            </form>

        </div>

    </div>

    <!-- LIST -->
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Senarai Enrolment
            </h5>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Title</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if($rows): ?>

                        <?php foreach($rows as $row): ?>

                        <tr>

                            <td>
                                <img src="../uploads/enrolmen/<?= htmlspecialchars($row['image']) ?>"
                                     class="preview-img"
                                     alt="<?= htmlspecialchars($row['title']) ?>">
                            </td>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($row['title']) ?>
                            </td>

                            <td>

                                <a href="?delete=<?= $row['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete data ini?')">
                                   Delete
                                </a>

                            </td>

                        </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="3"
                                class="text-center text-muted py-4">

                                Tiada data enrolment.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>