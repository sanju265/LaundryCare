<?php
session_start();
require_once __DIR__ . '/config/database.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login - LaundryCare</title><link rel="stylesheet" href="/laundrycare/assets/css/style.css"></head>
<body class="login"><div class="login-card">
<div class="login-logo">
  <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3 3 6l2 4 3-1v12h8V9l3 1 2-4-5-3a4 4 0 0 1-8 0z"/></svg>
</div>
<h1>LaundryCare</h1><p class="muted">Admin login</p>
<?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
<div class="field"><label>Username</label><input name="username" placeholder="Enter your username" autocomplete="username" required autofocus></div>
<div class="field"><label>Password</label>
  <div class="pw-wrap"><input type="password" id="pw" name="password" placeholder="Enter your password" autocomplete="current-password" required><button type="button" class="pw-toggle" onclick="var i=document.getElementById('pw');i.type=i.type==='password'?'text':'password';this.textContent=i.type==='password'?'Show':'Hide'">Show</button></div>
</div>
<button class="btn" type="submit">Login</button>
</form>
<p class="login-foot">Laundry Management System</p>
</div></body></html>