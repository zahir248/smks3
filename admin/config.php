<?php

$conn = mysqli_connect("localhost","root","","database_sekolah");

if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}

?>