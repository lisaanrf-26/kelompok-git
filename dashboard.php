<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'config/database.php';

// Proses Tambah Gudang
if (isset($_POST['tambah_gudang'])) {
    $nama_gudang = mysqli_real_escape_string($conn, $_POST['nama_gudang']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    mysqli_query($conn, "INSERT INTO storage_unit (nama_gudang, lokasi) VALUES ('$nama_gudang', '$lokasi')");
    header("Location: dashboard.php");
    exit;
}

// Proses Tambah Vendor
if (isset($_POST['tambah_vendor'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_vendor']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak_vendor']);
    mysqli_query($conn, "INSERT INTO vendor_supplier (nama, kontak) VALUES ('$nama', '$kontak')");
    header("Location: dashboard.php");
    exit;
}

// Proses Tambah Barang
if (isset($_POST['tambah_barang'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jenis_barang = mysqli_real_escape_string($conn, $_POST['jenis_barang']);
    $kuantitas_stok = (int)$_POST['kuantitas_stok'];
    $harga = (float)$_POST['harga'];
    $serial_number = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $id_gudang = (int)$_POST['id_gudang'];
    $id_vendor = (int)$_POST['id_vendor'];

    mysqli_query($conn, "INSERT INTO inventory (nama_barang, jenis_barang, kuantitas_stok, harga, serial_number, id_gudang, id_vendor) 
                         VALUES ('$nama_barang', '$jenis_barang', '$kuantitas_stok', '$harga', '$serial_number', '$id_gudang', '$id_vendor')");
    header("Location: dashboard.php");
    exit;
}

// Pencarian Barang
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT i.*, s.nama_gudang, v.nama AS nama_vendor 
          FROM inventory i 
          LEFT JOIN storage_unit s ON i.id_gudang = s.id_gudang 
          LEFT JOIN vendor_supplier v ON i.id_vendor = v.id_vendor";
if ($keyword != '') {
    $query .= " WHERE i.nama_barang LIKE '%$keyword%' OR i.jenis_barang LIKE '%$keyword%' OR i.serial_number LIKE '%$keyword%'";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Inventory System</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 20px; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .container { margin-top: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #dee2e6; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #f1f3f5; }
        .alert-danger { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 10px; border: 1px solid #f5c6cb; }
        .form-inline { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; }
        .form-inline input, .form-inline select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #bd2130; }
        .logout { color: #dc3545; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Dashboard Admin - Sistem Inventory</h2>
        <div>
            Halo, <strong><?php echo $_SESSION['admin']; ?></strong> | 
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">

        <!-- ALERT STOK HABIS -->
        <?php
        $cek_habis = mysqli_query($conn, "SELECT * FROM inventory WHERE kuantitas_stok = 0");
        while ($habis = mysqli_fetch_assoc($cek_habis)) {
            echo "<div class='alert-danger'><strong>PERINGATAN!</strong> Stok untuk barang <b>{$habis['nama_barang']}</b> (Serial: {$habis['serial_number']}) telah HABIS!</div>";
        }
        ?>

        <!-- FORM INPUT DATA -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Form Tambah Gudang -->
            <div class="card">
                <h3>Tambah Gudang Baru</h3>
                <form method="POST">
                    <div style="margin-bottom: 10px;">
                        <input type="text" name="nama_gudang" placeholder="Nama Gudang" required style="width: 100%; padding: 8px;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="text" name="lokasi" placeholder="Lokasi Gudang" required style="width: 100%; padding: 8px;">
                    </div>
                    <button type="submit" name="tambah_gudang" class="btn">Simpan Gudang</button>
                </form>
            </div>

            <!-- Form Tambah Vendor -->
            <div class="card">
                <h3>Tambah Vendor / Supplier</h3>
                <form method="POST">
                    <div style="margin-bottom: 10px;">
                        <input type="text" name="nama_vendor" placeholder="Nama Perusahaan Vendor" required style="width: 100%; padding: 8px;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="text" name="kontak_vendor" placeholder="Nomor Telepon Vendor" required style="width: 100%; padding: 8px;">
                    </div>
                    <button type="submit" name="tambah_vendor" class="btn">Simpan Vendor</button>
                </form>
            </div>
        </div>

        <!-- Form Tambah Barang Inventory -->
        <div class="card">
            <h3>Tambah Stok Barang Inventory</h3>
            <form method="POST">
                <div class="form-inline">
                    <input type="text" name="nama_barang" placeholder="Nama Barang" required>
                    <input type="text" name="jenis_barang" placeholder="Jenis / Kategori" required>
                    <input type="number" name="kuantitas_stok" placeholder="Jumlah Stok" required>
                    <input type="number" step="0.01" name="harga" placeholder="Harga Barang" required>
                    <input type="text" name="serial_number" placeholder="Serial Number / Barcode" required>
                    
                    <select name="id_gudang" required>
                        <option value="">Pilih Gudang</option>
                        <?php 
                        $gudang = mysqli_query($conn, "SELECT * FROM storage_unit");
                        while($g = mysqli_fetch_assoc($gudang)){
                            echo "<option value='{$g['id_gudang']}'>{$g['nama_gudang']} ({$g['lokasi']})</option>";
                        }
                        ?>
                    </select>

                    <select name="id_vendor" required>
                        <option value="">Pilih Vendor</option>
                        <?php 
                        $vendor = mysqli_query($conn, "SELECT * FROM vendor_supplier");
                        while($v = mysqli_fetch_assoc($vendor)){
                            echo "<option value='{$v['id_vendor']}'>{$v['nama']}</option>";
                        }
                        ?>
                    </select>

                    <button type="submit" name="tambah_barang" class="btn">Tambah Barang</button>
                </div>
            </form>
        </div>

        <!-- TABEL PEMANTAUAN STOK -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Pemantauan Stok Barang</h3>
                <form method="GET" style="display: flex; gap: 5px;">
                    <input type="text" name="search" placeholder="Cari nama/jenis/serial..." value="<?php echo htmlspecialchars($keyword); ?>" style="padding: 6px; width: 250px;">
                    <button type="submit" class="btn">Cari</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Serial Number</th>
                        <th>Lokasi Gudang</th>
                        <th>Vendor / Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $stok_style = ($row['kuantitas_stok'] == 0) ? "color: red; font-weight: bold;" : "";
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['jenis_barang']) . "</td>";
                            echo "<td style='$stok_style'>" . $row['kuantitas_stok'] . "</td>";
                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                            echo "<td>" . htmlspecialchars($row['serial_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_gudang']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_vendor']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center;'>Tidak ada data ditemukan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>