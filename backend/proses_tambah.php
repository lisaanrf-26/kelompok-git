<?php
require_once "koneksi.php";

// --- PROSES TAMBAH GUDANG ---
if (isset($_POST['tambah_gudang'])) {
    $nama_gudang = trim($_POST['nama_gudang']);
    $lokasi      = trim($_POST['lokasi']);

    $stmt = mysqli_prepare($conn, "INSERT INTO storage_unit (name, location) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $nama_gudang, $lokasi);

    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal menambah gudang: " . mysqli_stmt_error($stmt));
    }

    header("Location: dashboard.php");
    exit();
}

// --- PROSES TAMBAH VENDOR ---
if (isset($_POST['tambah_vendor'])) {
    $nama_vendor   = trim($_POST['nama_vendor']);
    $kontak_vendor = trim($_POST['kontak_vendor']);
    // Catatan: kolom item_name di tabel vendor_supplier NOT NULL,
    // tapi form dashboard belum punya input untuk ini.
    // Sementara diisi string kosong supaya INSERT tidak gagal.
    $item_name_vendor = '';

    $stmt = mysqli_prepare($conn, "INSERT INTO vendor_supplier (name, contact, item_name) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nama_vendor, $kontak_vendor, $item_name_vendor);

    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal menambah vendor: " . mysqli_stmt_error($stmt));
    }

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
        "INSERT INTO inventory (item_name, item_type, stock, price, serial_number, storage_id, vendor_id)
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

    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal menambah barang: " . mysqli_stmt_error($stmt));
    }

    header("Location: dashboard.php");
    exit();
}