<?php

$title = "Admin";

require_once "config/database.php";

require_once "includes/header.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name =
        $_POST["name"];

    $contact =
        $_POST["contact"];

    $email =
        $_POST["email"];

    $password =
        $_POST["password"];


    $password_hash =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );


    $stmt = $pdo->prepare(

        "INSERT INTO admin

        (
            name,
            contact,
            email,
            password
        )

        VALUES (?, ?, ?, ?)"

    );


    $stmt->execute([

        $name,

        $contact,

        $email,

        $password_hash

    ]);


    header(
        "Location: admin.php"
    );

    exit;
}


$admins = $pdo
    ->query(
        "SELECT
            id,
            name,
            contact,
            email

         FROM admin

         ORDER BY id DESC"
    )
    ->fetchAll();

?>


<div class="panel">


<h2>
Tambah Admin
</h2>


<form method="POST">


<div class="form-group">

<label>
Nama
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
Email
</label>

<input
    type="email"
    name="email"
    required>

</div>


<div class="form-group">

<label>
Password
</label>

<input
    type="password"
    name="password"
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
Daftar Admin
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
Email
</th>

</tr>


<?php foreach (
    $admins as $admin
): ?>

<tr>

<td>
<?= htmlspecialchars(
    $admin["name"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $admin["contact"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $admin["email"]
) ?>
</td>

</tr>

<?php endforeach; ?>


</table>

</div>