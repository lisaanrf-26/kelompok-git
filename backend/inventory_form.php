<?php
$title = "Form Inventory";
require_once "config/database.php";
require_once "includes/header.php";

$id =
    (int)($_GET["id"] ?? 0);
$data = [

    "item_name" => "",
    "item_type" => "",
    "stock" => 0,
    "storage_id" => "",
    "serial_number" => "",
    "price" => 0,
    "vendor_id" => ""
];


if ($id > 0) {

    $stmt = $pdo->prepare(
        "SELECT *
         FROM inventory
         WHERE id = ?"
    );

    $stmt->execute([$id]);

    $data =
        $stmt->fetch();

}


$storages = $pdo
    ->query(
        "SELECT *
         FROM storage_unit
         ORDER BY name"
    )
    ->fetchAll();


$vendors = $pdo
    ->query(
        "SELECT *
         FROM vendor_supplier
         ORDER BY name"
    )
    ->fetchAll();


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $item_name =
        $_POST["item_name"];

    $item_type =
        $_POST["item_type"];

    $stock =
        $_POST["stock"];

    $storage_id =
        $_POST["storage_id"];

    $serial_number =
        $_POST["serial_number"];

    $price =
        $_POST["price"];

    $vendor_id =
        $_POST["vendor_id"];


    if ($id > 0) {

        $stmt = $pdo->prepare(

            "UPDATE inventory

             SET
                item_name = ?,
                item_type = ?,
                stock = ?,
                storage_id = ?,
                serial_number = ?,
                price = ?,
                vendor_id = ?

             WHERE id = ?"

        );


        $stmt->execute([

            $item_name,

            $item_type,

            $stock,

            $storage_id,

            $serial_number,

            $price,

            $vendor_id,

            $id

        ]);

    } else {


        $stmt = $pdo->prepare(

            "INSERT INTO inventory

            (
                item_name,
                item_type,
                stock,
                storage_id,
                serial_number,
                price,
                vendor_id
            )

            VALUES
            (?, ?, ?, ?, ?, ?, ?)"

        );


        $stmt->execute([

            $item_name,

            $item_type,

            $stock,

            $storage_id,

            $serial_number,

            $price,

            $vendor_id

        ]);

    }


    header(
        "Location: inventory.php"
    );

    exit;

}

?>


<div class="panel">


<form method="POST">


<div class="form-group">

<label>
Nama Barang
</label>

<input
    type="text"
    name="item_name"
    required
    value="<?= htmlspecialchars(
        $data["item_name"]
    ) ?>">

</div>


<div class="form-group">

<label>
Jenis Barang
</label>

<input
    type="text"
    name="item_type"
    required
    value="<?= htmlspecialchars(
        $data["item_type"]
    ) ?>">

</div>


<div class="form-group">

<label>
Jumlah Stok
</label>

<input
    type="number"
    name="stock"
    min="0"
    required
    value="<?= $data["stock"] ?>">

</div>


<div class="form-group">

<label>
Serial Number
</label>

<input
    type="text"
    name="serial_number"
    required
    value="<?= htmlspecialchars(
        $data["serial_number"]
    ) ?>">

</div>


<div class="form-group">

<label>
Harga
</label>

<input
    type="number"
    name="price"
    min="0"
    required
    value="<?= $data["price"] ?>">

</div>


<div class="form-group">

<label>
Gudang
</label>

<select
    name="storage_id"
    required>

<option value="">
-- Pilih Gudang --
</option>


<?php foreach (
    $storages as $storage
): ?>

<option
    value="<?= $storage["id"] ?>">

<?= htmlspecialchars(
    $storage["name"]
) ?>

-

<?= htmlspecialchars(
    $storage["location"]
) ?>

</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>
Vendor / Supplier
</label>

<select
    name="vendor_id"
    required>

<option value="">
-- Pilih Vendor --
</option>


<?php foreach (
    $vendors as $vendor
): ?>

<option
    value="<?= $vendor["id"] ?>">

<?= htmlspecialchars(
    $vendor["name"]
) ?>

</option>

<?php endforeach; ?>

</select>

</div>


<button
    type="submit">

Simpan

</button>


<a
    href="inventory.php"
    class="btn">

Batal

</a>


</form>

</div>


<?php

require_once "includes/footer.php";

?>