<?php

require_once __DIR__ . "/koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = mysqli_real_escape_string($conn, trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email' LIMIT 1");
    $admin = mysqli_fetch_assoc($result);

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
<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — STOCKROOM</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="login-shell">

  <div class="login-visual">
    <div class="crate-field"></div>
    <div class="wordmark"><span class="dot"></span> STOCKROOM</div>

    <div class="pitch">
      <h2>Tahu stok Anda,<br>sebelum konsumen tahu.</h2>
      <p>Pantau kuantitas, lokasi gudang, dan vendor dalam satu panel — dan dapatkan peringatan sebelum barang benar‑benar habis.</p>
    </div>

    <div class="stat-row">
      <div class="stat"><b>3</b><span>gudang terhubung</span></div>
      <div class="stat"><b>24/7</b><span>pemantauan stok</span></div>
      <div class="stat"><b>1×</b><span>klik untuk cek barang</span></div>
    </div>
  </div>

  <div class="login-form-wrap">
    <form class="login-card" id="loginForm" method="POST">
      <div class="eyebrow">Panel Admin</div>
      <h1>Masuk ke akun Anda</h1>
      <p class="sub">Gunakan email dan kata sandi admin yang terdaftar.</p>

      <?php if ($error != ""): ?>
      <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="field">
        <label for="email">Email admin</label>
        <input type="email" id="email" name="email" placeholder="nama@gudangku.id" autocomplete="username" required>
      </div>
      <div class="field">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
      </div>

      <button type="submit" name="login" class="btn btn-primary">Masuk</button>

      <p class="register-link">
        Belum punya akun? <a href="register.php">Buat akun baru</a>
      </p>
    </form>
  </div>

</div>

</body>
</html>