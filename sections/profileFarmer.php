<?php
require '../db.php';
require '../auth.php';

$user = $_SESSION['user'];
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2"><i class="fas fa-user me-2"></i>Your Profile</h1>
</div>

<div class="card mb-4">
  <div class="card-body">
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
    <p><strong>Logged in as:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Session started:</strong> <?= date('Y-m-d H:i:s', $_SESSION['login_time'] ?? time()) ?></p>
  </div>
</div>

<a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>