<?php

$servername = "lcoalhost";
$username = "root";
$password = "";
$dbname = "elearning_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi Gagal: ". mysqli_connect_error());
}

?>