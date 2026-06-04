<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
$pdo = getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID berita tidak dijumpai");
}

// GET DATA BERITA
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    die("Berita tidak wujud");
}

// HANDLE UPDATE
if (isset($_POST['update'])) {

    try {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $imageName = $news['image']; // default lama

        // kalau upload gambar baru
        if (!empty($_FILES['image']['name'])) {

            $img = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];

            $targetPath = "../uploads/" . $img;
            move_uploaded_file($tmp, $targetPath);

            // delete gambar lama (optional tapi bagus)
            if (!empty($news['image']) && file_exists("../uploads/" . $news['image'])) {
                unlink("../uploads/" . $news['image']);
            }

            $imageName = $img;
        }

        // UPDATE DB
        $stmt = $pdo->prepare("
            UPDATE news 
            SET title = ?, slug = ?, content = ?, image = ?, published_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $title,
            $slug,
            $content,
            $imageName,
            $id
        ]);

        $_SESSION['msg_success'] = "Berita berjaya dikemaskini!";
        header("Location: homepage.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['msg_error'] = "Gagal update berita!";
        header("Location: homepage.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm p-4">

        <h3 class="mb-4">Edit Berita</h3>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Tajuk</label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($news['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label>Gambar Sekarang</label><br>

                <?php if (!empty($news['image'])): ?>
                    <img src="../uploads/<?= $news['image'] ?>" width="200" class="rounded mb-2">
                <?php else: ?>
                    <p class="text-muted">Tiada gambar</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>Tukar / Tambah Gambar</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" id="content"><?= htmlspecialchars($news['content']) ?></textarea>
            </div>

            <button type="submit" name="update" class="btn btn-primary">
                Update Berita
            </button>
            
            <a href="homepage.php" class="btn btn-secondary mb-3">
                ← Back
            </a>

        </form>

    </div>

</div>

<script>
tinymce.init({
    selector: '#content'
});
</script>

</body>
</html>