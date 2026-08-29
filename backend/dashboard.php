<?php

require_once "koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// --- PENCARIAN & PEMANTAUAN STOK ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';

if ($search !== '') {
    $query_inventory = "SELECT i.*, s.name AS storage_name, v.name AS vendor_name
                        FROM inventory i
                        LEFT JOIN storage_unit s ON s.id = i.storage_id
                        LEFT JOIN vendor_supplier v ON v.id = i.vendor_id
                        WHERE i.item_name LIKE '%$search%'
                           OR i.item_type LIKE '%$search%'
                           OR i.serial_number LIKE '%$search%'";
} else {
    $query_inventory = "SELECT i.*, s.name AS storage_name, v.name AS vendor_name
                        FROM inventory i
                        LEFT JOIN storage_unit s ON s.id = i.storage_id
                        LEFT JOIN vendor_supplier v ON v.id = i.vendor_id";
}
$result_inventory = mysqli_query($conn, $query_inventory);

// --- STATISTIK RINGKASAN ---
$totalItems   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inventory"));
$stokHabis    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM inventory WHERE stock = 0"));
$totalStorage = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM storage_unit"));
$totalVendor  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM vendor_supplier"));

// --- DROPDOWN GUDANG & VENDOR ---
$dropdownGudang = mysqli_query($conn, "SELECT * FROM storage_unit");
$dropdownVendor = mysqli_query($conn, "SELECT * FROM vendor_supplier");

// --- BARANG STOK HABIS (untuk alert) ---
$outOfStockResult = mysqli_query($conn,
    "SELECT i.*, s.name AS storage_name, v.name AS vendor_name
     FROM inventory i
     JOIN storage_unit s ON s.id = i.storage_id
     JOIN vendor_supplier v ON v.id = i.vendor_id
     WHERE i.stock = 0"
);

$adminName = $_SESSION["admin_name"] ?? "Admin";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Inventory System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="topbar">
        <div>
            <span class="brand-mark">INV / MANIFEST SYSTEM</span>
            <h1>Dashboard Admin</h1>
        </div>
        <div class="topbar-user">
            Halo, <strong><?= htmlspecialchars($adminName) ?></strong>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="shell">

        <!-- Ringkasan cepat -->
        <div class="stat-strip">
            <div class="stat-chip">
                <span class="label">Total SKU</span>
                <span class="value"><?= $totalItems ?></span>
            </div>
            <div class="stat-chip alert">
                <span class="label">Stok Habis</span>
                <span class="value"><?= $stokHabis ?></span>
            </div>
            <div class="stat-chip">
                <span class="label">Gudang Aktif</span>
                <span class="value"><?= $totalStorage ?></span>
            </div>
            <div class="stat-chip">
                <span class="label">Vendor Terdaftar</span>
                <span class="value"><?= $totalVendor ?></span>
            </div>
        </div>

        <div class="tear-line"></div>

        <!-- ALERT STOK HABIS -->
        <?php if (mysqli_num_rows($outOfStockResult) > 0): ?>
            <?php while ($item = mysqli_fetch_assoc($outOfStockResult)): ?>
            <div class="alert-banner">
                <span class="tag">Habis</span>
                <span><strong><?= htmlspecialchars($item['item_name']) ?></strong> (Serial: <?= htmlspecialchars($item['serial_number']) ?>) — stok saat ini 0, perlu restock segera.</span>
            </div>
            <?php endwhile; ?>
            <div class="tear-line"></div>
        <?php endif; ?>

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
                        <input type="text" name="item_name" placeholder="Nama Barang" required>
                    </div>
                    <div class="field">
                        <label>Jenis</label>
                        <input type="text" name="item_type" placeholder="Kategori" required>
                    </div>
                    <div class="field">
                        <label>Stok</label>
                        <input type="number" name="stock" placeholder="Jumlah" required>
                    </div>
                    <div class="field">
                        <label>Harga</label>
                        <input type="number" step="0.01" name="price" placeholder="Harga" required>
                    </div>
                    <div class="field">
                        <label>Serial Number</label>
                        <input type="text" name="serial_number" placeholder="SN / Barcode" required>
                    </div>
                    <div class="field">
                        <label>Gudang</label>
                        <select name="storage_id" required>
                            <option value="">Pilih Gudang</option>
                            <?php while ($g = mysqli_fetch_assoc($dropdownGudang)): ?>
                                <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Vendor</label>
                        <select name="vendor_id" required>
                            <option value="">Pilih Vendor</option>
                            <?php while ($v = mysqli_fetch_assoc($dropdownVendor)): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?></option>
                            <?php endwhile; ?>
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
                <form method="GET" class="search-row">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama / jenis / serial...">
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
                    <?php if (mysqli_num_rows($result_inventory) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result_inventory)): ?>
                        <?php $stokClass = ($row['stock'] == 0) ? 'stock-cell stok-habis' : 'mono'; ?>
                        <tr>
                            <td class="mono"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['item_name']) ?></td>
                            <td><span class="pill"><?= htmlspecialchars($row['item_type']) ?></span></td>
                            <td class="<?= $stokClass ?>"><?= $row['stock'] ?></td>
                            <td class="mono">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td class="mono"><?= htmlspecialchars($row['serial_number']) ?></td>
                            <td><?= htmlspecialchars($row['storage_name']) ?></td>
                            <td><?= htmlspecialchars($row['vendor_name']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty-row">Tidak ada data ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html> 