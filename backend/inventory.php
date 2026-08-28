<?php
$title = "Inventory";
require_once "config/database.php";
require_once "includes/header.php";
$search =
    $_GET["search"] ?? "";
if ($search != "") {
    $stmt = $pdo->prepare(
        "SELECT
            i.*,
            s.name AS storage_name,
            v.name AS vendor_name
         FROM inventory i
         JOIN storage_unit s
            ON s.id = i.storage_id
         JOIN vendor_supplier v
            ON v.id = i.vendor_id
         WHERE
            i.item_name LIKE ?
            OR i.item_type LIKE ?
            OR i.serial_number LIKE ?
            OR v.name LIKE ?
         ORDER BY i.id DESC"
    );

    $like = "%".$search."%";
    $stmt->execute([
        $like,
        $like,
        $like,
        $like
    ]);

} else {

    $stmt = $pdo->query(
        "SELECT
            i.*,
            s.name AS storage_name,
            v.name AS vendor_name
         FROM inventory i
         JOIN storage_unit s
            ON s.id = i.storage_id
         JOIN vendor_supplier v
            ON v.id = i.vendor_id
         ORDER BY i.id DESC"

    );

}
$inventory =
    $stmt->fetchAll();
?>


<div class="panel">
<a
    href="inventory_form.php"
    class="btn">
    + Tambah Barang
</a>
<br>
<br>


<form method="GET">
<input
    type="text"
    name="search"
    placeholder="Cari barang..."
    value="<?= htmlspecialchars($search) ?>">
<br>
<br>

<button
    type="submit">

Cari
</button>

</form>
<br>
<table>
<tr>
<th>Nama Barang</th>
<th>Jenis</th>
<th>Stok</th>
<th>Gudang</th>
<th>Serial Number</th>
<th>Harga</th>
<th>Vendor</th>
<th>Aksi</th>
</tr>
<?php foreach ($inventory as $item): ?>

<tr>


<td>
<?= htmlspecialchars(
    $item["item_name"]
) ?>
</td>


<td>
<?= htmlspecialchars(
    $item["item_type"]
) ?>
</td>


<td
class="<?= $item["stock"] == 0
    ? "danger-text"
    : "" ?>">

<?= $item["stock"] ?>

</td>


<td>
<?= htmlspecialchars(
    $item["storage_name"]
) ?>
</td>


<td>
<?= htmlspecialchars(
    $item["serial_number"]
) ?>
</td>


<td>

Rp <?= number_format(
    $item["price"],
    0,
    ",",
    "."
) ?>

</td>


<td>
<?= htmlspecialchars(
    $item["vendor_name"]
) ?>
</td>


<td>

<a
    href="inventory_form.php?id=<?= $item["id"] ?>"
    class="btn">

    Edit

</a>


<a
    href="inventory_delete.php?id=<?= $item["id"] ?>"
    class="btn btn-danger"
    data-confirm="Yakin ingin menghapus?">

    Hapus

</a>

</td>


</tr>

<?php endforeach; ?>

</table>

</div>