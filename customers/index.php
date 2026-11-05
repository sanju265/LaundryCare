<?php
require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../config/database.php'; $title='Customers';
$q=trim($_GET['q']??'');
if($q){$st=$pdo->prepare("SELECT * FROM customers WHERE name ILIKE ? OR phone ILIKE ? OR COALESCE(email,'') ILIKE ? ORDER BY id DESC");$like="%$q%";$st->execute([$like,$like,$like]);$rows=$st->fetchAll();}else{$rows=$pdo->query('SELECT * FROM customers ORDER BY id DESC')->fetchAll();}
include __DIR__.'/../includes/header.php'; ?>
<div class="page-head"><div><h1>Customers</h1><div class="muted">Manage laundry customers.</div></div><a class="btn" href="add.php">+ Add Customer</a></div>
<form class="search" method="get"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Search name, phone or email"><button class="btn secondary">Search</button><a class="btn secondary" href="index.php">Clear</a></form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Actions</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=htmlspecialchars($r['phone'])?></td><td><?=htmlspecialchars($r['email']??'')?></td><td><div class="actions"><a class="btn secondary" href="edit.php?id=<?=$r['id']?>">Edit</a><a class="btn danger" href="delete.php?id=<?=$r['id']?>" onclick="return confirm('Delete this customer?')">Delete</a></div></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5">No customers found.</td></tr><?php endif; ?></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
