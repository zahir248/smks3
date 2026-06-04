<?php
session_start();

// 🔒 protect (superadmin sahaja)
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin'){
    header("Location: login.php");
    exit();
}

// include header
include "includes/header.php";
?>

<!-- CONTENT DALAM NI SAHAJA -->

<h4 class="mb-3 mt-0">Dashboard</h4>

<div class="card-box">
    <h5>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h5>
    <p>Anda login sebagai <b>Superadmin</b>.</p>
</div>

<br>

<div class="row">

    <!-- CARD 1 -->
    <div class="col-md-4">
        <div class="card-box">
            <h6>Total Users</h6>
            <p style="font-size:24px;">--</p>
        </div>
    </div>

    <!-- CARD 2 -->
    <div class="col-md-4">
        <div class="card-box">
            <h6>Admin</h6>
            <p style="font-size:24px;">--</p>
        </div>
    </div>

    <!-- CARD 3 -->
    <div class="col-md-4">
        <div class="card-box">
            <h6>Superadmin</h6>
            <p style="font-size:24px;">--</p>
        </div>
    </div>

</div>

<br>

<div class="card-box">
    <h6>System Info</h6>
    <p>Tarikh: <?= date("d M Y") ?></p>
    <p>Masa: <?= date("h:i A") ?></p>
</div>

<?php
// include footer
include "includes/footer.php";
?>