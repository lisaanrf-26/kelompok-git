<?php
$host = "127.0.0.1";
$port = 3307; // sesuai port MySQL yang jalan di XAMPP Control Panel kamu
$user = "root";
$pass = "";
$db   = "inventory_db";

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
