<?php
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Isi username dan password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - GNG Panel</title>
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
  <div class="alert">Please login first</div>

  <?php if ($error): ?>
    <div class="alert" style="background:#fee2e2;border-color:#fecaca;color:#b91c1c;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">Login</div>
    <form method="POST" action="login.php">
      <div class="form-group">
        <label>Username</label>
        <input name="username" type="text" placeholder="Your username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input name="password" type="password" placeholder="Your password" required>
      </div>
      <div class="checkbox-row">
        <input type="checkbox" style="width:auto;">
        <label style="margin:0;">Stay login?</label>
      </div>
      <button type="submit">⮕ Log in</button>
    </form>

    <div class="link-text">
      Don't have an account yet?
      <a href="register.php">Register here</a>
    </div>
  </div>
</div>
</body>
</html>
