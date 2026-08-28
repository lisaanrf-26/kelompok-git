<?php

include 'koneksi.php';

// --- PENCARIAN & PEMANTAUAN STOK ---
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query_inventory = "SELECT i.*, g.nama_gudang, v.nama AS nama_vendor 
                        FROM inventory i 
                        LEFT JOIN storage_unit g ON i.id_gudang = g.id_gudang 
                        LEFT JOIN vendor_supplier v ON i.id_vendor = v.id_vendor
                        WHERE i.nama_barang LIKE '%$search%' 
                        OR i.jenis_barang LIKE '%$search%' 
                        OR i.serial_number LIKE '%$search%'";
} else {
    $query_inventory = "SELECT i.*, g.nama_gudang, v.nama AS nama_vendor 
                        FROM inventory i 
                        LEFT JOIN storage_unit g ON i.id_gudang = g.id_gudang 
                        LEFT JOIN vendor_supplier v ON i.id_vendor = v.id_vendor";
}
$result_inventory = mysqli_query($conn, $query_inventory);

// --- HITUNG STATISTIK (RINGKASAN) ---
$total_sku        = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inventory"));
$stok_habis       = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inventory WHERE kualitas_stok = 0"));
$gudang_aktif     = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM storage_unit"));
$vendor_terdaftar = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM vendor_supplier"));

// --- AMBIL DATA DROPDOWN GUDANG & VENDOR ---
$dropdown_gudang = mysqli_query($conn, "SELECT * FROM storage_unit");
$dropdown_vendor = mysqli_query($conn, "SELECT * FROM vendor_supplier");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Inventory System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="topbar">
        <div>
            <span class="brand-mark">INV / MANIFEST SYSTEM</span>
            <h1>Dashboard Admin</h1>
        </div>
        <div class="topbar-user">
            Halo, <strong>Admin</strong>
            <a href="login.html" class="logout">Logout</a>
        </div>
    </div>

    <div class="shell">

        <!-- Ringkasan cepat -->
        <div class="stat-strip">
            <div class="stat-chip">
                <span class="label">Total SKU</span>
                <span class="value"><?php echo $total_sku; ?></span>
            </div>
            <div class="stat-chip alert">
                <span class="label">Stok Habis</span>
                <span class="value"><?php echo $stok_habis; ?></span>
            </div>
            <div class="stat-chip">
                <span class="label">Gudang Aktif</span>
                <span class="value"><?php echo $gudang_aktif; ?></span>
            </div>
            <div class="stat-chip">
                <span class="label">Vendor Terdaftar</span>
                <span class="value"><?php echo $vendor_terdaftar; ?></span>
            </div>
        </div>

        <div class="tear-line"></div>

        <!-- FORM INPUT DATA -->
        <div class="grid-2">
            <!-- Form Tambah Gudang -->
            <div class="card">
                <div class="card-head">
                    <div>
                        <span class="card-eyebrow">Master Data</span>
                        <h3>Tambah Gudang Baru</h3>
                    </div>
                </div>
                <form method="POST" action="proses_tambah.php">
                    <div class="form-group">
                        <label>Nama Gudang</label>
                        <input type="text" name="nama_gudang" placeholder="cth. Gudang Utara" required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" placeholder="cth. Surabaya" required>
                    </div>
                    <button type="submit" name="tambah_gudang" class="btn">Simpan Gudang</button>
                </form>
            </div>

            <!-- Form Tambah Vendor -->
            <div class="card">
                <div class="card-head">
                    <div>
                        <span class="card-eyebrow">Master Data</span>
                        <h3>Tambah Vendor / Supplier</h3>
                    </div>
                </div>
                <form method="POST" action="proses_tambah.php">
                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input type="text" name="nama_vendor" placeholder="cth. PT Vendor Satu" required>
                    </div>
                    <div class="form-group">
                        <label>Kontak</label>
                        <input type="text" name="kontak_vendor" placeholder="Nomor telepon" required>
                    </div>
                    <button type="submit" name="tambah_vendor" class="btn">Simpan Vendor</button>
                </form>
            </div>
        </div>

        <!-- Form Tambah Barang Inventory -->
        <div class="card">
            <div class="card-head">
                <div>
                    <span class="card-eyebrow">Stok Masuk</span>
                    <h3>Tambah Stok Barang Inventory</h3>
                </div>
            </div>
            <form method="POST" action="proses_tambah.php">
                <div class="form-inline">
                    <div class="field">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" placeholder="Nama Barang" required>
                    </div>
                    <div class="field">
                        <label>Jenis</label>
                        <input type="text" name="jenis_barang" placeholder="Kategori" required>
                    </div>
                    <div class="field">
                        <label>Stok</label>
                        <input type="number" name="kuantitas_stok" placeholder="Jumlah" required>
                    </div>
                    <div class="field">
                        <label>Harga</label>
                        <input type="number" step="0.01" name="harga" placeholder="Harga" required>
                    </div>
                    <div class="field">
                        <label>Serial Number</label>
                        <input type="text" name="serial_number" placeholder="SN / Barcode" required>
                    </div>
                    <div class="field">
                        <label>Gudang</label>
                        <select name="id_gudang" required>
                            <option value="">Pilih Gudang</option>
                            <?php while($g = mysqli_fetch_assoc($dropdown_gudang)) { ?>
                                <option value="<?php echo $g['id_gudang']; ?>"><?php echo $g['nama_gudang']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Vendor</label>
                        <select name="id_vendor" required>
                            <option value="">Pilih Vendor</option>
                            <?php while($v = mysqli_fetch_assoc($dropdown_vendor)) { ?>
                                <option value="<?php echo $v['id_vendor']; ?>"><?php echo $v['nama']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="tambah_barang" class="btn btn-amber">Tambah Barang</button>
                </div>
            </form>
        </div>

        <!-- TABEL PEMANTAUAN STOK -->
        <div class="card">
            <div class="card-head">
                <div>
                    <span class="card-eyebrow">Manifest</span>
                    <h3>Pemantauan Stok Barang</h3>
                </div>
                <form method="GET" action="" class="search-row">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari nama / jenis / serial...">
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
                        <th>Gudang</th>
                        <th>Vendor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result_inventory) > 0) {
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result_inventory)) {
                            $stok_class = ($row['kualitas_stok'] == 0) ? 'stock-cell stok-habis' : 'mono';
                    ?>
                    <tr>
                        <td class="mono"><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><span class="pill"><?php echo htmlspecialchars($row['jenis_barang']); ?></span></td>
                        <td class="<?php echo $stok_class; ?>"><?php echo $row['kualitas_stok']; ?></td>
                        <td class="mono">Rp <?php echo number_format($row['harga'], 2, ',', '.'); ?></td>
                        <td class="mono"><?php echo htmlspecialchars($row['serial_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_gudang']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_vendor']); ?></td>
                    </tr>
                    <?php 
                        }
                    } else { 
                    ?>
                    <tr>
                        <td colspan="8" class="empty-row">Tidak ada data ditemukan.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
=======
$title = "Dashboard";
require_once "config/database.php";
require_once "includes/header.php";

// TOTAL BARANG
$totalItems = $pdo
    ->query(
        "SELECT COUNT(*)
         FROM inventory"
    )
    ->fetchColumn();


// TOTAL STOK
$totalStock = $pdo
    ->query(
        "SELECT
            COALESCE(SUM(stock),0)
         FROM inventory"
    )
    ->fetchColumn();


// TOTAL GUDANG
$totalStorage = $pdo
    ->query(
        "SELECT COUNT(*)
         FROM storage_unit"
    )
    ->fetchColumn();


// TOTAL VENDOR
$totalVendor = $pdo
    ->query(
        "SELECT COUNT(*)
         FROM vendor_supplier"
    )
    ->fetchColumn();


// BARANG HABIS
$outOfStock = $pdo->query(
    "SELECT
        i.*,
        s.name AS storage_name,
        v.name AS vendor_name

     FROM inventory i

     JOIN storage_unit s
        ON s.id = i.storage_id

     JOIN vendor_supplier v
        ON v.id = i.vendor_id

     WHERE i.stock = 0"
)->fetchAll();

?>

<div class="cards">
<div class="card">
<h3>Total Barang</h3>
<div class="number">
<?= $totalItems ?>

</div>
</div>


<div class="card">
<h3>Total Stok</h3>
<div class="number">
<?= $totalStock ?>
</div>
</div>


<div class="card">
<h3>Total Gudang</h3>
<div class="number">
<?= $totalStorage ?>
</div>
</div>


<div class="card">
<h3>Total Vendor</h3>
<div class="number">
<?= $totalVendor ?>
</div>
</div>
</div>


<div class="panel">
<h2>Alert Stok Habis</h2>

<?php if (count($outOfStock) == 0): ?>
<div class="alert">
Tidak ada barang yang habis.
</div>

<?php else: ?>
<div class="alert">
⚠️ Ada barang yang stoknya habis!
</div>
<table>
<tr>
<th>Nama Barang</th>
<th>Stok</th>
<th>Gudang</th>
<th>Vendor</th>
</tr>

<?php foreach ($outOfStock as $item): ?>
<tr>
<td>
<?= htmlspecialchars(
    $item["item_name"]
) ?>
</td>
<td class="danger-text">
0
</td>

<td>
<?= htmlspecialchars(
    $item["storage_name"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $item["vendor_name"]
) ?>
</td>

</tr>

<?php endforeach; ?>
</table>
<?php endif; ?>
</div>
