<?php
session_start();

if(!isset($_SESSION['username'])){
header("Location: login.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
font-family:Segoe UI;
background:#f4f6f9;
}

.sidebar{
width:230px;
height:100vh;
background:#343a40;
position:fixed;
color:white;
padding-top:20px;
}

.sidebar h4{
text-align:center;
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:12px 20px;
color:#ddd;
text-decoration:none;
}

.sidebar a:hover{
background:#495057;
color:white;
}

.main-content{
margin-left:230px;
padding:30px;
}

.card-box{
background:white;
padding:25px;
border-radius:8px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

</style>

</head>

<body>

<div class="sidebar">

<h4>Sistem Sekolah</h4>

<a href="#">Dashboard</a>
<a href="crud.php">CRUD Guru</a>
<a href="#">Data Murid</a>
<a href="#">Data Guru</a>
<a href="#">Pengumuman</a>
<a href="logout.php">Logout</a>

</div>

<div class="main-content">

<h3>Dashboard</h3>

<div class="row">

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
<h5>Tingkatan Tertinggi</h5>
<h2>5</h2>
</div>
</div>

</div>

</div>

</body>
</html>