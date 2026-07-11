<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/bootstrap.php';

smks3_ensure_session();

if (!smks3_is_editor()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Kaedah tidak dibenarkan.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
if (!is_array($data)) {
    $data = $_POST;
}

smks3_require_csrf($data);

$preview = $data['preview'] ?? $data['edit_preview'] ?? null;
$previewOn = $preview === true || $preview === 1 || $preview === '1' || $preview === 'true' || $preview === 'on';

if (!smks3_set_edit_preview($previewOn)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Gagal simpan pilihan.']);
    exit;
}

echo json_encode([
    'ok' => true,
    'edit_preview' => smks3_get_edit_preview(),
]);
