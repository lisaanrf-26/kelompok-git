<?php

$title = "Vendor / Supplier";

require_once "config/database.php";

require_once "includes/header.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name =
        $_POST["name"];

    $contact =
        $_POST["contact"];

    $item_name =
        $_POST["item_name"];


    $stmt = $pdo->prepare(

        "INSERT INTO vendor_supplier

        (
            name,
            contact,
            item_name
        )

        VALUES (?, ?, ?)"

    );


    $stmt->execute([

        $name,

        $contact,

        $item_name

    ]);


    header(
        "Location: vendor.php"
    );

    exit;
}


$vendors = $pdo
    ->query(
        "SELECT *
         FROM vendor_supplier
         ORDER BY id DESC"
    )
    ->fetchAll();

?>


<div class="panel">


<h2>
Tambah Vendor / Supplier
</h2>


<form method="POST">


<div class="form-group">

<label>
Nama Vendor
</label>

<input
    type="text"
    name="name"
    required>

</div>


<div class="form-group">

<label>
Kontak
</label>

<input
    type="text"
    name="contact">

</div>


<div class="form-group">

<label>
Nama Barang
</label>

<input
    type="text"
    name="item_name"
    required>

</div>


<button
    type="submit">

Simpan

</button>


</form>

</div>


<div class="panel">


<h2>
Daftar Vendor
</h2>


<table>

<tr>

<th>
Nama
</th>

<th>
Kontak
</th>

<th>
Barang
</th>

</tr>


<?php foreach (
    $vendors as $vendor
): ?>

<tr>

<td>
<?= htmlspecialchars(
    $vendor["name"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $vendor["contact"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $vendor["item_name"]
) ?>
</td>

</tr>

<?php endforeach; ?>


</table>

</div>
