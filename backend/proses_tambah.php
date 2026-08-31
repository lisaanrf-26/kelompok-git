<?php
require_once "koneksi.php";

// --- PROSES TAMBAH GUDANG ---
if (isset($_POST['tambah_gudang'])) {
    $nama_gudang = trim($_POST['nama_gudang']);
    $lokasi      = trim($_POST['lokasi']);

    $stmt = mysqli_prepare($conn, "INSERT INTO storage_unit (nama_gudang, lokasi) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $nama_gudang, $lokasi);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php");
    exit();
}

// --- PROSES TAMBAH VENDOR ---
if (isset($_POST['tambah_vendor'])) {
    $nama_vendor   = trim($_POST['nama_vendor']);
    $kontak_vendor = trim($_POST['kontak_vendor']);

    $stmt = mysqli_prepare($conn, "INSERT INTO vendor_supplier (nama, kontak) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $nama_vendor, $kontak_vendor);
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php");
    exit();
}

// --- PROSES TAMBAH BARANG INVENTORY ---
if (isset($_POST['tambah_barang'])) {
    $nama_barang   = trim($_POST['nama_barang']);
    $jenis_barang  = trim($_POST['jenis_barang']);
    $kuantitas     = (int) $_POST['kuantitas_stok'];
    $harga         = (float) $_POST['harga'];
    $serial_number = trim($_POST['serial_number']);
    $id_gudang     = (int) $_POST['id_gudang'];
    $id_vendor     = (int) $_POST['id_vendor'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO inventory (nama_barang, jenis_barang, kualitas_stok, harga, serial_number, id_gudang, id_vendor)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param(
        $stmt,
        "ssidsii",
        $nama_barang,
        $jenis_barang,
        $kuantitas,
        $harga,
        $serial_number,
        $id_gudang,
        $id_vendor
    );
    mysqli_stmt_execute($stmt);

    header("Location: dashboard.php");
    exit();
}
