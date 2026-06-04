<?php
session_start();
require_once __DIR__ . "/../config/database.php";

$error = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        $error = "Sila isi semua maklumat.";
    } else {

        $pdo = getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data){

            if(password_verify($password, $data['password'])){

                // 🔒 hanya superadmin boleh login
                if($data['role'] !== 'superadmin'){
                    $error = "Akses hanya untuk superadmin.";
                } else {

                    $_SESSION['username'] = $data['username'];
                    $_SESSION['role'] = $data['role'];

                    header("Location: dashboard.php");
                    exit();
                }

            } else {
                $error = "Password salah.";
            }

        } else {
            $error = "Username tidak wujud.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Superadmin Login</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
body{
    background:#f4f6f9;
}

.login-box{
    max-width:400px;
    margin:120px auto;
}
</style>
</head>

<body>

<div class="container login-box">

    <div class="card p-4 shadow">

        <!-- 🔥 LOGO clickable -->
        <div class="text-center mb-3">
            <a href="../index.php">
                <img src="../images/logosmks3 new.png"
                     style="width:90px; height:90px; object-fit:cover; border-radius:50%; cursor:pointer;">
            </a>
        </div>

        <h4 class="text-center mb-3">Superadmin Login</h4>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <input type="text" name="username" class="form-control mb-3" autocomplete="off" placeholder="Username" required>

            <!-- 🔥 PASSWORD + EYE ICON -->
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>

                <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </span>
            </div>

            <button class="btn btn-primary w-100" name="login">Login</button>
        </form>

    </div>

</div>

<!-- 🔥 JS toggle password -->
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