<?php
require __DIR__ . '/app/bootstrap.php';
$pdo = getConnection();

$pdo->exec('DELETE FROM fpk_misi_visi WHERE kategori = \'__repair_test__\'');
$pdo->exec("SET SESSION sql_mode = CONCAT(@@sql_mode, ',NO_AUTO_VALUE_ON_ZERO')");
$pdo->exec("INSERT INTO fpk_misi_visi (id, kategori, content) VALUES (0, '__repair_test__', 'should be reassigned')");

echo "before:\n";
foreach ($pdo->query("SELECT id, kategori FROM fpk_misi_visi WHERE kategori = '__repair_test__'") as $r) {
    echo 'id=' . var_export($r['id'], true) . PHP_EOL;
}

// New process needed to reset static $done — call repair path by including freshly
// smks3_ensure_table_auto_id already marked done for this process from bootstrap? No, only when called.
// But wait — PageController isn't loaded. First call should run.

// Bypass static $done by using a one-off direct repair simulation via reflection isn't available.
// Re-require won't reset static. Use a subprocess for ensure.

echo "---\n";
