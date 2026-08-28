<?php
require_once __DIR__ . "/auth.php";
require_login();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">
<title>
<?= $title ?? "Inventory Admin" ?>
</title>

<link
    rel="stylesheet"
    href="assets/style.css">

</head>


<body>

<div class="layout">


<!-- SIDEBAR -->

<div class="sidebar">

<h2>Inventory</h2>

<p>Admin Panel</p>


<a href="dashboard.php">
Dashboard
</a>

<a href="inventory.php">
Inventory
</a>

<a href="storage.php">
Gudang
</a>

<a href="vendor.php">
Vendor / Supplier
</a>

<a href="admin.php">
Admin
</a>

<a href="logout.php">
Logout
</a>

</div>


<!-- CONTENT -->

<div class="content">

<h1>
<?= $title ?? "Dashboard" ?>
</h1>