<?php
require 'config.php';
require_login();

$userId   = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Stranger';

// ambil data user
$stmt = $pdo->prepare('SELECT balance, role FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$balance = $user['balance'] ?? 0;
$role    = $user['role'] ?? 'reseller';

// ambil history license
$stmt = $pdo->prepare('SELECT * FROM licenses WHERE user_id = ? ORDER BY created_at DESC LIMIT 50');
$stmt->execute([$userId]);
$licenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - GNG Panel</title>
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
  <!-- Welcome -->
  <div class="card">
    <div class="card-header">Welcome <?= htmlspecialchars($username) ?></div>
  </div>

  <!-- Balance + Generate -->
  <div class="card">
    <div class="balance-card">
      <div>🧾 Total Balance</div>
      <div class="balance-amount">$<?= number_format($balance, 2) ?></div>
    </div>

    <div class="section-title">Generate New License</div>

    <form method="POST" action="generate_license.php">
      <div class="form-group">
        <label>Game</label>
        <select name="game">
          <option value="GNG">GNG</option>
        </select>
      </div>

      <div class="form-group">
        <label>Max Devices</label>
        <input type="number" name="max_devices" min="1" value="1">
      </div>

      <div class="form-group">
        <label>Duration Type</label>
        <select name="duration_type" id="duration-type">
          <option value="preset">Preset Duration</option>
          <option value="custom">Custom Duration</option>
        </select>
      </div>

      <div class="form-group" id="preset-wrapper">
        <label>Preset Duration</label>
        <select name="preset_duration" id="preset-duration">
          <option value="1 Days">1 Days</option>
          <option value="3 Days">3 Days</option>
          <option value="7 Days">7 Days</option>
          <option value="14 Days">14 Days</option>
          <option value="30 Days">30 Days</option>
        </select>
      </div>

      <div class="two-col" id="custom-wrapper" style="display:none;">
        <div class="form-group">
          <label>Days</label>
          <input type="number" name="custom_days" min="0" value="0">
        </div>
        <div class="form-group">
          <label>Hours</label>
          <input type="number" name="custom_hours" min="0" value="0">
        </div>
      </div>

      <button type="submit" style="margin-top:8px;">🔑 Generate License</button>
    </form>
  </div>

  <!-- Registration history -->
  <div class="card">
    <div class="section-title">Registration History</div>
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Plan</th>
            <th>Duration</th>
            <th>Devices</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($licenses as $lic): ?>
          <tr>
            <td>#<?= $lic['id'] ?></td>
            <td><?= htmlspecialchars($lic['plan']) ?></td>
            <td><?= htmlspecialchars($lic['game']) ?></td>
            <td><?= htmlspecialchars($lic['duration_value']) ?></td>
            <td><?= (int)$lic['max_devices'] ?> Dev</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Information -->
  <div class="card">
    <div class="section-title">Information</div>
    <div class="info-row">
      <div class="info-label">Roles</div>
      <div class="info-value"><?= htmlspecialchars(ucfirst($role)) ?></div>
    </div>
    <div class="info-row">
      <div class="info-label">Balance</div>
      <div class="info-value">$<?= number_format($balance, 2) ?></div>
    </div>
    <div class="info-row">
      <div class="info-label">Login Time</div>
      <div class="info-value">Just now</div>
    </div>
    <div class="info-row">
      <div class="info-label">Auto Logout</div>
      <div class="info-value">in 1 day</div>
    </div>

    <form action="logout.php" method="POST" style="margin-top:10px;">
      <button class="btn-outline" type="submit">Log out</button>
    </form>
  </div>
</div>

<script>
  const durationType = document.getElementById('duration-type');
  const presetWrapper = document.getElementById('preset-wrapper');
  const customWrapper = document.getElementById('custom-wrapper');

  durationType.addEventListener('change', () => {
    if (durationType.value === 'preset') {
      presetWrapper.style.display = 'block';
      customWrapper.style.display = 'none';
    } else {
      presetWrapper.style.display = 'none';
      customWrapper.style.display = 'grid';
    }
  });
</script>
</body>
</html>
