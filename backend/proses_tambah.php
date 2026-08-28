<?php
include 'koneksi.php';

// --- PROSES TAMBAH GUDANG ---
if (isset($_POST['tambah_gudang'])) {
    $nama_gudang = mysqli_real_escape_string($conn, $_POST['nama_gudang']);
    $lokasi      = mysqli_real_escape_string($conn, $_POST['lokasi']);

    $query = "INSERT INTO storage_unit (nama_gudang, lokasi) VALUES ('$nama_gudang', '$lokasi')";
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}

// --- PROSES TAMBAH VENDOR ---
if (isset($_POST['tambah_vendor'])) {
    $nama_vendor   = mysqli_real_escape_string($conn, $_POST['nama_vendor']);
    $kontak_vendor = mysqli_real_escape_string($conn, $_POST['kontak_vendor']);

    $query = "INSERT INTO vendor_supplier (nama, kontak) VALUES ('$nama_vendor', '$kontak_vendor')";
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}

// --- PROSES TAMBAH BARANG INVENTORY ---
if (isset($_POST['tambah_barang'])) {
    $nama_barang   = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis_barang  = mysqli_real_escape_string($conn, $_POST['jenis_barang']);
    $kuantitas     = (int) $_POST['kuantitas_stok'];
    $harga         = (float) $_POST['harga'];
    $serial_number = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $id_gudang     = (int) $_POST['id_gudang'];
    $id_vendor     = (int) $_POST['id_vendor'];

    $query = "INSERT INTO inventory (nama_barang, jenis_barang, kualitas_stok, harga, serial_number, id_gudang, id_vendor) 
              VALUES ('$nama_barang', '$jenis_barang', '$kuantitas', '$harga', '$serial_number', '$id_gudang', '$id_vendor')";
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}
?>