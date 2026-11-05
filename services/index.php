<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';$title='Services';$q=trim($_GET['q']??'');
if($q){$st=$pdo->prepare("SELECT * FROM services WHERE service_name ILIKE ? OR COALESCE(description,'') ILIKE ? ORDER BY id DESC");$like="%$q%";$st->execute([$like,$like]);$rows=$st->fetchAll();}else{$rows=$pdo->query('SELECT * FROM services ORDER BY id DESC')->fetchAll();}
include __DIR__.'/../includes/header.php';?>
<div class="page-head"><div><h1>Services</h1><div class="muted">Manage laundry services and pricing.</div></div><a class="btn" href="add.php">+ Add Service</a></div>
<form class="search" method="get"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Search service"><button class="btn secondary">Search</button><a class="btn secondary" href="index.php">Clear</a></form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>Service</th><th>Description</th><th>Price/kg</th><th>Availability</th><th>Actions</th></tr>
<?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['service_name'])?></td><td><?=htmlspecialchars($r['description']??'')?></td><td>₹<?=number_format($r['price_per_kg'],2)?></td><td><?= $r['is_active']?'Active':'Inactive' ?></td><td><div class="actions"><a class="btn secondary" href="edit.php?id=<?=$r['id']?>">Edit</a><a class="btn danger" href="delete.php?id=<?=$r['id']?>" onclick="return confirm('Delete this service?')">Delete</a></div></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="6">No services found.</td></tr><?php endif;?></table></div>
<?php include __DIR__.'/../includes/footer.php';?>
