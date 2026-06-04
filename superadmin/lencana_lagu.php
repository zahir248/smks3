<?php
session_start();
require_once __DIR__ . '/../config/database.php';
$pdo = getConnection();

/* =======================
   FETCH MAIN DATA
======================= */
$stmt = $pdo->query("SELECT * FROM lencana_lagu_sekolah WHERE id = 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$image = $data['image'] ?? '';

/* =======================
   UPDATE MAIN DATA
======================= */
if (isset($_POST['save_main'])) {

    $imageName = $image;

    if (!empty($_FILES['image']['name'])) {

        $folder = "../images/";
        $imageName = time() . '_' . $_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'], $folder . $imageName);
    }

    $stmt = $pdo->prepare("
        UPDATE lencana_lagu_sekolah
        SET moto = :moto,
            lirik = :lirik,
            lirik_penggubah = :lirik_penggubah,
            lirik_penulis = :lirik_penulis,
            image = :image
        WHERE id = 1
    ");

    $stmt->execute([
        ':moto' => $_POST['moto'],
        ':lirik' => $_POST['lirik'],
        ':lirik_penggubah' => $_POST['lirik_penggubah'],
        ':lirik_penulis' => $_POST['lirik_penulis'],
        ':image' => $imageName
    ]);

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}

/* =======================
   FETCH ITEMS (LENCANA)
======================= */
$stmt = $pdo->query("SELECT * FROM lencana_item ORDER BY sort_order ASC, id ASC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =======================
   ADD ITEM
======================= */
if (isset($_POST['add_item'])) {

    $stmt = $pdo->prepare("
        INSERT INTO lencana_item (title, description, sort_order)
        VALUES (:title, :description, 0)
    ");

    $stmt->execute([
        ':title' => $_POST['title'],
        ':description' => $_POST['description']
    ]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* =======================
   DELETE ITEM
======================= */
if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("DELETE FROM lencana_item WHERE id = ?");
    $stmt->execute([$_GET['delete']]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* =======================
   UPDATE ITEMS
======================= */
if (isset($_POST['save_items'])) {

    foreach ($_POST['id'] as $i => $id) {

        $stmt = $pdo->prepare("
            UPDATE lencana_item
            SET title = :title,
                description = :description,
                sort_order = :sort_order
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':title' => $_POST['title'][$i],
            ':description' => $_POST['description'][$i],
            ':sort_order' => $_POST['sort_order'][$i]
        ]);
    }

    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit;
}
?>

<?php include 'includes/header.php'; ?>
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
<div class="main-content">

    <a href="manage_category.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

<h2>Lencana & Lagu Sekolah</h2>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success">Berjaya disimpan</div>
<?php endif; ?>

<!-- ================= MAIN FORM ================= -->
<form method="POST" enctype="multipart/form-data">

<div class="card p-4 mb-4">

    <h5>Maklumat Utama</h5>

    <label>Moto</label>
    <input type="text" name="moto" class="form-control mb-2"
        value="<?= htmlspecialchars($data['moto'] ?? '') ?>">

    <label>Lirik</label>
    <textarea name="lirik" class="form-control mb-2" rows="6"><?= htmlspecialchars($data['lirik'] ?? '') ?></textarea>

    <label>Penggubah</label>
    <input type="text" name="lirik_penggubah" class="form-control mb-2"
        value="<?= htmlspecialchars($data['lirik_penggubah'] ?? '') ?>">

    <label>Penulis</label>
    <input type="text" name="lirik_penulis" class="form-control mb-2"
        value="<?= htmlspecialchars($data['lirik_penulis'] ?? '') ?>">

    <label>Gambar</label>
    <input type="file" name="image" class="form-control mb-2">

    <?php if($image): ?>
        <img src="../images/<?= $image ?>" width="120">
    <?php endif; ?>

    <button class="btn btn-success mt-3" name="save_main">
        Simpan Maklumat
    </button>

</div>

</form>

<!-- ================= ADD ITEM ================= -->
<form method="POST">

<div class="card p-4 mb-4">

    <h5>Tambah Item Lencana</h5>

    <input type="text" name="title" class="form-control mb-2" placeholder="Title">

    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

    <button class="btn btn-primary" name="add_item">
        Tambah Item
    </button>

</div>

</form>

<!-- ================= LIST ITEM ================= -->
<form method="POST">

<div class="card p-4">

<?php foreach($items as $i => $row): ?>

    <div class="border p-3 mb-3 rounded">

        <input type="hidden" name="id[]" value="<?= $row['id'] ?>">

        <input type="text" name="title[]" class="form-control mb-2"
            value="<?= htmlspecialchars($row['title']) ?>">

        <textarea name="description[]" class="form-control mb-2"><?= htmlspecialchars($row['description']) ?></textarea>

        <input type="number" name="sort_order[]" class="form-control mb-2"
            value="<?= $row['sort_order'] ?>">

        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">
            Delete
        </a>

    </div>

<?php endforeach; ?>

<button class="btn btn-primary" name="save_items">
    Simpan Susunan
</button>

</div>

</form>

</div>

<?php include 'includes/footer.php'; ?>