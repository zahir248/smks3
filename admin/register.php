<?php
include "config.php";

if(isset($_POST['register'])){

$username=$_POST['username'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);

$query="INSERT INTO users(username,password)
VALUES('$username','$password')";

mysqli_query($conn,$query);

echo "<script>
alert('Admin berjaya didaftarkan');
window.location='login.php';
</script>";

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register Admin</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>

body{
background:#f4f6f9;
font-family:Segoe UI;
}

.register-container{
width:380px;
margin:120px auto;
background:white;
padding:35px;
border-radius:10px;
box-shadow:0 6px 20px rgba(0,0,0,0.15);
}

.title{
text-align:center;
margin-bottom:25px;
font-weight:bold;
}

/* CSS untuk logo */
.logo{
    display: block;       /* center the image */
    margin: 0 auto 15px;  /* auto left & right, 15px bottom spacing */
    width: 100px;          /* kecilkan width */
    height: 100px;         /* kecilkan height */
    object-fit: cover;    /* maintain aspect ratio */
    border-radius: 50%;   /* optional: buat bulat */
}
</style>

</head>

<body>

<div class="register-container">
<img src="../images/logosmks3.jpg" class="logo" alt="Admin Logo">
<h3 class="title">Register Admin</h3>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" class="form-control" name="username" required>
</div>

<div class="mb-3">
<label>Password</label>

<div class="input-group">
<input type="password" class="form-control" id="password" name="password" required>

<span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
<i class="bi bi-eye" id="eyeIcon"></i>
</span>

</div>
</div>

<button class="btn btn-success w-100" name="register">Register</button>

</form>

<div class="text-center mt-3">
<a href="login.php">Back to Login</a>
</div>

</div>
<script>

function togglePassword(){

var password=document.getElementById("password");
var icon=document.getElementById("eyeIcon");

if(password.type==="password"){

password.type="text";
icon.classList.remove("bi-eye");
icon.classList.add("bi-eye-slash");

}else{

password.type="password";
icon.classList.remove("bi-eye-slash");
icon.classList.add("bi-eye");

}

}

</script>
</body>
</html>