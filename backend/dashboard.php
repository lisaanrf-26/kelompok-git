<?php
require_once "koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$adminName = $_SESSION["admin_name"] ?? "Admin";

// =========================================================
// PROSES TAMBAH DATA (dijalankan sebelum query tampilan)
// =========================================================

// --- TAMBAH GUDANG ---
if (isset($_POST['tambah_gudang'])) {
    $nama_gudang = trim($_POST['nama_gudang']);
    $stmt = mysqli_prepare($conn, "INSERT INTO storage_unit (name) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $nama_gudang);
    mysqli_stmt_execute($stmt);
    header("Location: dashboard.php");
    exit;
}

// --- TAMBAH VENDOR ---
if (isset($_POST['tambah_vendor'])) {
    $nama_vendor = trim($_POST['nama_vendor']);
    $stmt = mysqli_prepare($conn, "INSERT INTO vendor_supplier (name) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $nama_vendor);
    mysqli_stmt_execute($stmt);
    header("Location: dashboard.php");
    exit;
}

// --- TAMBAH BARANG ---
if (isset($_POST['tambah_barang'])) {
    $item_name     = trim($_POST['item_name']);
    $item_type     = trim($_POST['item_type']);
    $stock         = (int)$_POST['stock'];
    $price         = (float)$_POST['price'];
    $serial_number = trim($_POST['serial_number']);
    $storage_id    = (int)$_POST['storage_id'];
    $vendor_id     = (int)$_POST['vendor_id'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO inventory (item_name, item_type, stock, price, serial_number, storage_id, vendor_id)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param(
        $stmt,
        "ssidsii",
        $item_name,
        $item_type,
        $stock,
        $price,
        $serial_number,
        $storage_id,
        $vendor_id
    );
    mysqli_stmt_execute($stmt);
    header("Location: dashboard.php");
    exit;
}

// =========================================================
// PENCARIAN & DATA INVENTORY
// =========================================================

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $likeSearch = "%$search%";
    $stmt = mysqli_prepare(
        $conn,
        "SELECT i.*, s.name AS storage_name, v.name AS vendor_name
         FROM inventory i
         LEFT JOIN storage_unit s ON s.id = i.storage_id
         LEFT JOIN vendor_supplier v ON v.id = i.vendor_id
         WHERE i.item_name LIKE ? OR i.item_type LIKE ? OR i.serial_number LIKE ?"
    );
    mysqli_stmt_bind_param($stmt, "sss", $likeSearch, $likeSearch, $likeSearch);
    mysqli_stmt_execute($stmt);
    $result_inventory = mysqli_stmt_get_result($stmt);
} else {
    $result_inventory = mysqli_query(
        $conn,
        "SELECT i.*, s.name AS storage_name, v.name AS vendor_name
         FROM inventory i
         LEFT JOIN storage_unit s ON s.id = i.storage_id
         LEFT JOIN vendor_supplier v ON v.id = i.vendor_id"
    );
}

// =========================================================
// STATISTIK RINGKASAN
// =========================================================

$totalItems   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM inventory"));
$stokHabis    = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM inventory WHERE stock = 0"));
$totalStorage = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM storage_unit"));
$totalVendor  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM vendor_supplier"));

// =========================================================
// DROPDOWN GUDANG & VENDOR
// =========================================================

$dropdownGudang = mysqli_query($conn, "SELECT * FROM storage_unit");
$dropdownVendor = mysqli_query($conn, "SELECT * FROM vendor_supplier");

// =========================================================
// BARANG STOK HABIS (untuk alert) — pakai LEFT JOIN
// biar barang tanpa gudang/vendor tetap muncul di alert
// =========================================================

$outOfStockResult = mysqli_query(
    $conn,
    "SELECT i.*, s.name AS storage_name, v.name AS vendor_name
     FROM inventory i
     LEFT JOIN storage_unit s ON s.id = i.storage_id
     LEFT JOIN vendor_supplier v ON v.id = i.vendor_id
     WHERE i.stock = 0"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Inventory System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="topbar">
        <div class="topbar-title">
            <h1>Dashboard Admin</h1>
        </div>
        <div class="topbar-user">
            Halo, <strong><?= htmlspecialchars($adminName) ?></strong>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">

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
                <span>
                    <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                    (Serial: <?= htmlspecialchars($item['serial_number']) ?>)
                    — stok saat ini 0, perlu restock segera.
                </span>
            </div>
            <?php endwhile; ?>
            <div class="tear-line"></div>
        <?php endif; ?>

        <!-- FORM INPUT DATA -->
        <div class="grid-2">
            <!-- Form Tambah Gudang -->
            <div class="card">
                <h3>Tambah Gudang</h3>
                <form method="POST" class="form-inline">
                    <div class="field">
                        <label>Nama Gudang</label>
                        <input type="text" name="nama_gudang" placeholder="Nama Gudang" required>
                    </div>
                    <button type="submit" name="tambah_gudang" class="btn">Tambah Gudang</button>
                </form>
            </div>

            <!-- Form Tambah Vendor -->
            <div class="card">
                <h3>Tambah Vendor</h3>
                <form method="POST" class="form-inline">
                    <div class="field">
                        <label>Nama Vendor</label>
                        <input type="text" name="nama_vendor" placeholder="Nama Vendor" required>
                    </div>
                    <button type="submit" name="tambah_vendor" class="btn">Tambah Vendor</button>
                </form>
            </div>
        </div>

        <!-- Form Tambah Barang -->
        <div class="card">
            <h3>Tambah Barang</h3>
            <form method="POST" class="form-inline">
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
            </form>
        </div>

        <div class="tear-line"></div>

        <!-- TABEL INVENTORY -->
        <div class="card">
            <div class="card-header">
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
                            <td class="mono">Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
                            <td class="mono"><?= htmlspecialchars($row['serial_number']) ?></td>
                            <td><?= htmlspecialchars($row['storage_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['vendor_name'] ?? '-') ?></td>
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
