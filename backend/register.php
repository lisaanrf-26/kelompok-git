<?php

require_once __DIR__ . "/koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? "");
    $contact  = trim($_POST["contact"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {
        $error = "Nama, email, dan password wajib diisi.";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {

        // Cek apakah email sudah dipakai
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $cek = mysqli_query($conn, "SELECT id FROM admin WHERE email = '$emailEsc' LIMIT 1");

        if (mysqli_num_rows($cek) > 0) {
            $error = "Email sudah terdaftar. Silakan masuk.";
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn,
                "INSERT INTO admin (name, contact, email, password) VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "ssss", $name, $contact, $email, $password_hash);

            if (mysqli_stmt_execute($stmt)) {

                // Langsung login otomatis
                $newId = mysqli_insert_id($conn);
                $_SESSION["admin_id"] = $newId;
                $_SESSION["admin_name"] = $name;

                header("Location: dashboard.php");
                exit;

            } else {
                $error = "Gagal mendaftar. Coba lagi.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Akun — STOCKROOM</title>
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
      <h2>Daftar sebagai admin<br>dan mulai kelola stok.</h2>
      <p>Buat akun untuk mengakses panel admin, memantau stok, gudang, dan vendor.</p>
    </div>
  </div>

  <div class="login-form-wrap">
    <form class="login-card" method="POST">
      <div class="eyebrow">Panel Admin</div>
      <h1>Buat akun baru</h1>
      <p class="sub">Isi data di bawah untuk membuat akun admin.</p>

      <?php if ($error != ""): ?>
      <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="field">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" placeholder="Nama lengkap" required>
      </div>
      <div class="field">
        <label for="contact">Kontak</label>
        <input type="text" id="contact" name="contact" placeholder="Nomor HP (opsional)">
      </div>
      <div class="field">
        <label for="email">Email admin</label>
        <input type="email" id="email" name="email" placeholder="nama@gudangku.id" autocomplete="username" required>
      </div>
      <div class="field">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password" required>
      </div>
      <div class="field">
        <label for="confirm_password">Konfirmasi kata sandi</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi kata sandi" autocomplete="new-password" required>
      </div>

      <button type="submit" class="btn btn-primary">Daftar</button>

      <p class="register-link">
        Sudah punya akun? <a href="login.php">Masuk</a>
      </p>
    </form>
  </div>

</div>

</body>
</html>