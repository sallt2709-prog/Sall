<?php
require 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $referral = trim($_POST['referral'] ?? '');

    if (!$username || !$password || !$confirm) {
        $error = 'Semua field wajib diisi.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi tidak sama.';
    } else {
        // cek username
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username sudah dipakai.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            $success = 'Register berhasil. Silakan login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - GNG Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
  <div class="navbar-title">
    <div class="navbar-logo">◎</div>
    <span>GNG Panel</span>
  </div>
  <div class="navbar-menu">
    <span></span><span></span><span></span>
  </div>
</div>

<div class="container">
  <div class="card">
    <div class="card-header">Register</div>

    <?php if ($error): ?>
      <div class="alert" style="background:#fee2e2;border-color:#fecaca;color:#b91c1c;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert" style="background:#dcfce7;border-color:#bbf7d0;color:#166534;">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <div class="form-group">
        <label>Username</label>
        <input name="username" type="text" placeholder="Your username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input name="password" type="password" placeholder="Your password" required>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input name="confirm" type="password" placeholder="Confirm password" required>
      </div>
      <div class="form-group">
        <label>Referral Code</label>
        <input name="referral" type="text" placeholder="Referral code">
      </div>
      <button type="submit">⮕ Register</button>
    </form>

    <div class="link-text">
      Already have an account?
      <a href="login.php">Login here</a>
    </div>
  </div>
</div>
</body>
</html>
