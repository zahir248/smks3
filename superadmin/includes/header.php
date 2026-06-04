<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            margin:0;
            font-family:Segoe UI;
            background:#f4f6f9;
        }
        
        /* TOPBAR */
        .topbar{
            height:100px;
            background:linear-gradient(90deg,#00c6ff,#0072ff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 30px;
        }
        
        /* BESARKAN LOGO + TEXT */
        .topbar .logo{
            display:flex;
            align-items:center;
            font-weight:bold;
            font-size:24px;
        }
        
        .topbar .logo img{
            width:50px;
            height:50px;
            object-fit:cover;
            margin-right:15px;
        }
        
        /* USER NAME */
        .topbar .user{
            font-size:16px;
        }
        
        /* SIDEBAR */
        .sidebar{
            width:230px;
            height:calc(100vh - 100px);
            background:#ffffff;
            position:absolute;
            top:100px; /* ikut height topbar */
            left:0;
            border-right:1px solid #eee;
            padding-top:10px;
        }
        
        /* SIDEBAR LINK */
        .sidebar a{
            display:flex;
            align-items:center;
            padding:12px 20px;
            color:#333;
            text-decoration:none;
            transition:0.2s;
        }
        
        .sidebar a i{
            font-size:18px;
        }
        
        /* HOVER */
        .sidebar a:hover{
            background:#f1f1f1;
            padding-left:25px;
        }
        
        /* CONTENT */
        .content{
            margin-left:230px;
            margin-top:10px; /* ikut topbar */
            padding:20px;
        }
        
        .card-box{
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

<!-- TOPBAR -->
<div class="topbar">

    <div class="logo">
        <img src="../images/logosmks3 new.png">
        <span>SUPERADMIN SMK SEREMBAN 3</span>
    </div>

    <div class="user">
        <?= $_SESSION['username'] ?? 'Guest'; ?>
    </div>

</div>

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="dashboard.php">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    
    <a href="manage_page.php">
        <i class="bi bi-pencil-square"></i> Manage Page
    </a>

    <a href="register.php">
        <i class="bi bi-person-plus me-2"></i> Register
    </a>

    <a href="logout.php">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>

</div>

<!-- CONTENT START -->
<div class="content">