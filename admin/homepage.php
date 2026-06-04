<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
$pdo = getConnection();

/* =========================
   GET NEWS LIST
========================= */
$news_list = $pdo->query("
    SELECT * FROM news ORDER BY published_at DESC
")->fetchAll(PDO::FETCH_ASSOC);


/* =========================
   INSERT NEWS + PDF (ANTI 403 VERSION)
========================= */
if (isset($_POST['submit'])) {

    try {

        $title = trim($_POST['title']);
        $content = $_POST['description'];
        $year = $_POST['year'];

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $excerpt = substr(strip_tags($content), 0, 150);

        $pdfName = null;

        /* =========================
           SAFE PDF UPLOAD (WAF FRIENDLY)
        ========================= */
        if (!empty($_FILES['pdf_file']['name'])) {

            $file = $_FILES['pdf_file'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Upload error");
            }

            // folder (ABSOLUTE PATH - IMPORTANT)
            $folder = __DIR__ . "/../uploads/pdf/";

            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            // extension check
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                throw new Exception("Only PDF allowed");
            }

            // SAFE FILE NAME (NO SPACE, NO SPECIAL CHAR)
            $original = pathinfo($file['name'], PATHINFO_FILENAME);

            $safeName = preg_replace('/[^a-zA-Z0-9]+/', '-', $original);
            $safeName = trim($safeName, '-');

            // FINAL FILE NAME (VERY SAFE FOR MODSEC)
            $pdfName = date('Ymd_His') . '-' . $safeName . '.pdf';

            $destination = $folder . $pdfName;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new Exception("Upload failed (permission/path issue)");
            }
        }

        /* =========================
           INSERT DB
        ========================= */
        $stmt = $pdo->prepare("
            INSERT INTO news (title, slug, excerpt, content, status, published_at, year, pdf_file)
            VALUES (?, ?, ?, ?, 'published', NOW(), ?, ?)
        ");

        $stmt->execute([$title, $slug, $excerpt, $content, $year, $pdfName]);

        $_SESSION['msg_success'] = "Berita + PDF berjaya disimpan!";
        header("Location: homepage.php");
        exit();

    } catch (Exception $e) {

        $_SESSION['msg_error'] = $e->getMessage();
        header("Location: homepage.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Homepage Admin</title>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { font-family: 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; }
a { text-decoration:none; }

.sidebar {
    width:230px;
    height:100vh;
    background:#0d9488;
    position:fixed;
    color:white;
    padding-top:20px;
}

.sidebar h4 {
    text-align:center;
    margin-bottom:30px;
    font-weight:700;
}

.menu-item a,
.menu-title {
    display:block;
    padding:12px 15px;
    color:white;
    cursor:pointer;
    border-radius:8px;
}

.menu-item a:hover,
.menu-title:hover {
    background:#115e59;
}

.submenu {
    max-height:0;
    overflow:hidden;
    transition:0.3s;
    padding-left:10px;
}

.menu-item.active .submenu {
    max-height:300px;
}

.main-content {
    margin-left:230px;
    padding:30px;
}

.card-box {
    background:white;
    padding:25px;
    border-radius:8px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

.alert {
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h4>Sistem Sekolah</h4>

    <div class="menu-item">
        <a href="dashboard.php">Dashboard</a>
        <a href="homepage.php">Homepage (Berita)</a>
    </div>

    <div class="menu-item">
        <div class="menu-title" onclick="this.parentElement.classList.toggle('active')">
            Manage Page ⯈
        </div>
        <div class="submenu">
            <a href="#">Profil Sekolah</a>
            <a href="#">FPK</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main-content">

<?php if (!empty($_SESSION['msg_success'])): ?>
<div class="alert alert-success">
    <?= $_SESSION['msg_success']; unset($_SESSION['msg_success']); ?>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['msg_error'])): ?>
<div class="alert alert-danger">
    <?= $_SESSION['msg_error']; unset($_SESSION['msg_error']); ?>
</div>
<?php endif; ?>


<div class="card-box">
<h3>Senarai Berita</h3>

<table class="table table-bordered mt-3">
<tr>
    <th>#</th>
    <th>Tajuk</th>
    <th>Tahun</th>
    <th>Tarikh</th>
    <th>PDF</th>
    <th>Tindakan</th>
</tr>

<?php foreach ($news_list as $i => $n): ?>
<tr>
    <td><?= $i + 1 ?></td>
    <td><?= htmlspecialchars($n['title']) ?></td>

    <td><span class="badge bg-info"><?= $n['year'] ?></span></td>

    <td><?= $n['published_at'] ?></td>

    <td>
        <?php if (!empty($n['pdf_file'])): ?>
            <a href="view-pdf.php?file=<?= urlencode($n['pdf_file']) ?>" 
               class="btn btn-sm btn-info">
               Lihat PDF
            </a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    <td>
        <a href="edit-news.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete-news.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Anda pasti?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>


<div class="card-box">
<h3>Tambah Berita</h3>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" class="form-control mb-2" placeholder="Tajuk" required>

<label>Upload PDF:</label>
<input type="file" name="pdf_file" class="form-control mb-2" accept="application/pdf">

<select name="year" class="form-control mb-2">
    <option value="2026">2026</option>
    <option value="2025">2025</option>
</select>

<textarea id="description" name="description"></textarea>

<br>
<button class="btn btn-success" name="submit">Muat Naik</button>

</form>
</div>

</div>

<script>
tinymce.init({ selector:'#description' });
</script>

</body>
</html>