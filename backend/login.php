<?php

require_once __DIR__ . "/config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $query = $pdo->prepare(
        "SELECT * FROM admin WHERE email = ? LIMIT 1"
    );

    $query->execute([$email]);

    $admin = $query->fetch();

    if ($admin && password_verify($password, $admin["password"])) {

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_name"] = $admin["name"];

        header("Location: dashboard.php");
        exit;

    } else {

        $error = "Email atau password salah.";

    }
}
?>
<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Login Admin</title>

<link
    rel="stylesheet"
    href="assets/style.css">

</head>

<body>

<div class="login-container">

<div class="login-box">

<h1>Inventory Admin</h1>

<h2>Login</h2>


<?php if ($error != ""): ?>

<div class="alert">

<?= $error ?>

</div>

<?php endif; ?>


<form method="POST">

<label>Email</label>

<input
    type="email"
    name="email"
    required>


<label>Password</label>

<input
    type="password"
    name="password"
    required>


<button type="submit">

Login

</button>
</form>
</div>
</div>
</body>
</html>