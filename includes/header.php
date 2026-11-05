<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
$__path = $_SERVER['SCRIPT_NAME'] ?? '';
$__on = function (string $needle) use ($__path): string {
    return str_contains($__path, $needle) ? ' class="active"' : '';
};
$__user = $_SESSION['username'] ?? 'Admin';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($title ?? 'LaundryCare') ?></title>
<link rel="stylesheet" href="/laundrycare/assets/css/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="/laundrycare/dashboard.php">
    <span class="brand-mark">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3 3 6l2 4 3-1v12h8V9l3 1 2-4-5-3a4 4 0 0 1-8 0z"/></svg>
    </span>
    <span>LaundryCare</span>
  </a>
  <?php if (!empty($_SESSION['admin_id'])): ?>
  <nav>
    <a href="/laundrycare/dashboard.php"<?= $__on('dashboard.php') ?>>Overview</a>
    <a href="/laundrycare/customers/index.php"<?= $__on('/customers/') ?>>Customers</a>
    <a href="/laundrycare/services/index.php"<?= $__on('/services/') ?>>Services</a>
    <a href="/laundrycare/orders/index.php"<?= $__on('/orders/') ?>>Orders</a>
    <a href="/laundrycare/xml/import.php"<?= $__on('import.php') ?>>Import XML</a>
    <a href="/laundrycare/xml/export.php"<?= $__on('export.php') ?>>Export XML</a>
    <span class="user-chip" title="Signed in as <?= htmlspecialchars($__user) ?>"><span class="avatar"><?= htmlspecialchars(strtoupper(substr($__user, 0, 1))) ?></span><?= htmlspecialchars($__user) ?></span>
    <a href="/laundrycare/logout.php" class="signout">Sign out</a>
  </nav>
  <?php endif; ?>
</header>
<main class="container">