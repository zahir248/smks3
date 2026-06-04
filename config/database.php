<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'iesbcomm_smks3');
define('DB_USER', 'iesbcomm_smks3');
define('DB_PASS', 'D)(mBlY+o9_{+d+p');

function getConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch (PDOException $e) {
        die("DB ERROR: " . $e->getMessage());
    }
}