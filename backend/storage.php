<?php

$title = "Gudang";

require_once "config/database.php";

require_once "includes/header.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name =
        $_POST["name"];

    $location =
        $_POST["location"];


    $stmt = $pdo->prepare(

        "INSERT INTO storage_unit
        (
            name,
            location
        )

        VALUES (?, ?)"

    );


    $stmt->execute([
        $name,
        $location
    ]);


    header(
        "Location: storage.php"
    );

    exit;
}


$storages = $pdo
    ->query(
        "SELECT *
         FROM storage_unit
         ORDER BY id DESC"
    )
    ->fetchAll();

?>


<div class="panel">

<h2>
Tambah Gudang
</h2>


<form method="POST">


<div class="form-group">

<label>
Nama Gudang
</label>

<input
    type="text"
    name="name"
    required>

</div>


<div class="form-group">

<label>
Lokasi
</label>

<input
    type="text"
    name="location"
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
Daftar Gudang
</h2>


<table>

<tr>

<th>
Nama Gudang
</th>

<th>
Lokasi
</th>

</tr>


<?php foreach (
    $storages as $storage
): ?>

<tr>

<td>

<?= htmlspecialchars(
    $storage["name"]
) ?>

</td>


<td>

<?= htmlspecialchars(
    $storage["location"]
) ?>

</td>

</tr>

<?php endforeach; ?>


</table>

</div>