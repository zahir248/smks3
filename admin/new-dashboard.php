<?php
session_start();

// 🔒 PROTECT PAGE
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Baru</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

/* Sidebar */
.sidebar {
    width: 220px;
    height: 100vh;
    background: #0d9488;
    position: fixed;
    color: white;
    padding: 20px 0;
}

.sidebar h4 {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    padding: 12px 15px;
    color: white;
    text-decoration: none;
}

.sidebar a:hover {
    background: #115e59;
}

/* Main */
.main {
    margin-left: 220px;
    padding: 20px;
}

.card-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4>Sistem Sekolah</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="register.php">Register Admin</a>
    <a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <h2>Dashboard</h2>

    <div class="alert alert-success">
        Welcome, <b><?php echo $_SESSION['username']; ?></b>
    </div>

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card-box">
                <h5>Jumlah Murid</h5>
                <h2>644</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h5>Jumlah Guru</h5>
                <h2>43</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h5>Tingkatan</h5>
                <h2>5</h2>
            </div>
        </div>

    </div>

</div>

</body>
</html>