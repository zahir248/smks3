<?php
session_start();
session_destroy();
$idle = isset($_GET['idle']);
header('Location: ../' . ($idle ? '?login=1&idle=1' : ''));
exit;
