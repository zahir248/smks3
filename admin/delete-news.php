<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
$pdo = getConnection();

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['msg_error'] = "ID tidak sah!";
    header("Location: homepage.php");
    exit;
}

try {
    // 1. ambil data dulu (untuk image)
    $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$news) {
        $_SESSION['msg_error'] = "Berita tidak dijumpai!";
        header("Location: homepage.php");
        exit;
    }

    // 2. delete image kalau ada
    if (!empty($news['image'])) {
        $imagePath = "../uploads/" . $news['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // 3. delete row
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['msg_success'] = "Berita berjaya dipadam!";
    header("Location: homepage.php");
    exit;

} catch (Exception $e) {
    $_SESSION['msg_error'] = "Gagal padam berita!";
    header("Location: homepage.php");
    exit;
}