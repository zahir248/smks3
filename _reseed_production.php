<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$pdo = getConnection();

$pageKeys = [
    'pentaksiran-peperiksaan',
    'unit-pbd',
    'pbd-ppt',
    'pbd-ppt-tingkatan-1',
    'pbd-ppt-tingkatan-2',
    'pbd-ppt-tingkatan-3',
    'pbd-ppt-tingkatan-4',
    'pbd-ppt-tingkatan-5',
    'pbd-ppt-tingkatan-1-individu',
    'pbd-ppt-tingkatan-2-individu',
    'pbd-ppt-tingkatan-3-individu',
    'pbd-ppt-tingkatan-4-individu',
    'pbd-ppt-tingkatan-5-individu',
    'pbd-uasa',
    'pbd-uasa-individu',
    'pbd-penjaminan-kualiti',
    'pbd-pk-pemantauan',
    'pbd-pk-pementoran',
    'pbd-pk-pengesanan',
    'pbd-pk-penyelarasan',
];

$deleteStmt = $pdo->prepare('DELETE FROM kurikulum_card WHERE page_key = ?');

foreach ($pageKeys as $key) {
    $deleteStmt->execute([$key]);
    $deleted = $deleteStmt->rowCount();
    smks3_seed_kurikulum_page($pdo, $key);
    $count = $pdo->prepare('SELECT COUNT(*) FROM kurikulum_card WHERE page_key = ?');
    $count->execute([$key]);
    $n = (int) $count->fetchColumn();
    echo ($deleted > 0 ? "RESEEDED" : "SEEDED") . " {$key}: {$n} cards\n";
}

echo "\nDone. You can delete this file now.\n";
