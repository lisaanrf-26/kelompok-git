<?php

require_once "config/database.php";

require_once "includes/auth.php";

require_login();


$id =
    (int)($_GET["id"] ?? 0);


$stmt = $pdo->prepare(

    "DELETE FROM inventory
     WHERE id = ?"

);


$stmt->execute([$id]);


header(
    "Location: inventory.php"
);

exit;