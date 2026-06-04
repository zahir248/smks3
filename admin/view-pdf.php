<?php
$file = $_GET['file'] ?? '';
$file = basename(urldecode($file));

$path = __DIR__ . "/../uploads/pdf/" . $file;

if (!is_file($path)) {
    http_response_code(404);
    die("File tak jumpa");
}

if (!is_readable($path)) {
    http_response_code(403);
    die("File ada tapi tak boleh access");
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"" . basename($file) . "\"");
header("Content-Length: " . filesize($path));

readfile($path);
exit;