<?php
session_start();
require_once '../config/database.php';

$pdo = getConnection();

if(!isset($_GET['id'])) {
    die("ID tidak dijumpai");
}

$id = $_GET['id'];

/* ambil gambar dulu */
$stmt = $pdo->prepare("SELECT image FROM guru WHERE id = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if($data){

    /* delete file image */
    if(!empty($data['image']) && file_exists("../uploads/" . $data['image'])){
        unlink("../uploads/" . $data['image']);
    }

    /* delete record */
    $stmt = $pdo->prepare("DELETE FROM guru WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['message'] = "Guru berjaya dipadam!";
}

header("Location: crud.php");
exit;