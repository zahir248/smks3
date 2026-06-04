<?php
require_once __DIR__ . "/../config/database.php";

/**
 * Gunakan PDO connection dari database.php
 */
$pdo = getConnection();

/**
 * Optional: kalau kau masih ada file lama yang guna $conn (mysqli),
 * kita boleh "bridge" sementara — tapi ini hanya sementara sahaja.
 */
$conn = null;