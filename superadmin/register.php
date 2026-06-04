<?php
session_start();
require_once __DIR__ . "/../config/database.php";

// 🔒 protect
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin'){
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if(isset($_POST['register'])){

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if(empty($username) || empty($_POST['password']) || empty($role)){
        $error = "Sila lengkapkan semua maklumat.";
    } 
    elseif($role !== 'admin' && $role !== 'superadmin'){
        $error = "Role tidak sah.";
    } 
    else {

        $pdo = getConnection();

        // check duplicate
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
        $stmt->execute([$username]);

        if($stmt->rowCount() > 0){
            $error = "Username sudah wujud.";
        } else {

            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            
            if($stmt->execute([$username, $password, $role])){
                $success = "User berjaya didaftarkan.";
            } else {
                $error = "Ralat semasa daftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- 🔥 Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
body{
    background:#f4f6f9;
}

.container-box{
    max-width:400px;
    margin-top:80px;
}
</style>
</head>

<body>

<div class="container container-box">
    <div class="card p-4 shadow">

        <h4 class="text-center mb-3">Register User</h4>

        <!-- 🔥 ERROR / SUCCESS -->
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">

            <!-- USERNAME -->
            <input type="text" name="username" class="form-control mb-3" autocomplete="off" placeholder="Username" required>

            <!-- 🔥 PASSWORD WITH EYE ICON -->
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>

                <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </span>
            </div>

            <!-- ROLE -->
            <select name="role" class="form-control mb-3" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="superadmin">Superadmin</option>
            </select>

            <!-- SUBMIT -->
            <button class="btn btn-success w-100" name="register">Register</button>

        </form>

        <!-- BACK BUTTON -->
        <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali Dashboard</a>

    </div>
</div>

<!-- 🔥 TOGGLE PASSWORD SCRIPT -->
<script>
function togglePassword(){

    var password = document.getElementById("password");
    var icon = document.getElementById("eyeIcon");

    if(password.type === "password"){
        password.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}
</script>

</body>
</html>