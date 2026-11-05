<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';$title='Orders';$status=$_GET['status']??'';$q=trim($_GET['q']??'');
$sql="SELECT o.*,c.name customer,s.service_name FROM orders o JOIN customers c ON c.id=o.customer_id JOIN services s ON s.id=o.service_id WHERE 1=1";$params=[];
if($status!==''){$sql.=' AND o.status=?';$params[]=$status;}if($q!==''){$sql.=" AND (c.name ILIKE ? OR CAST(o.id AS TEXT) ILIKE ?)";$like="%$q%";$params[]=$like;$params[]=$like;}$sql.=' ORDER BY o.id DESC';$st=$pdo->prepare($sql);$st->execute($params);$rows=$st->fetchAll();
include __DIR__.'/../includes/header.php';?>
<div class="page-head"><div><h1>Orders</h1><div class="muted">Track customer laundry orders.</div></div><a class="btn" href="add.php">+ New Order</a></div>
<form class="search" method="get"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Search customer/order ID"><select name="status"><option value="">All statuses</option><?php foreach(['Received','Washing','Ready','Delivered'] as $s):?><option <?=$status===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select><button class="btn secondary">Filter</button><a class="btn secondary" href="index.php">Clear</a></form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>Customer</th><th>Service</th><th>Weight</th><th>Due</th><th>Total</th><th>Status</th><th>Actions</th></tr>
<?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['customer'])?></td><td><?=htmlspecialchars($r['service_name'])?></td><td><?=$r['weight']?> kg</td><td><?=$r['due_date']?></td><td>₹<?=number_format($r['total_amount'],2)?></td><td><span class="badge <?=strtolower($r['status'])?>"><?=htmlspecialchars($r['status'])?></span></td><td><div class="actions"><a class="btn secondary" href="edit.php?id=<?=$r['id']?>">Edit</a><a class="btn danger" href="delete.php?id=<?=$r['id']?>" onclick="return confirm('Delete this order?')">Delete</a></div></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="8">No orders found.</td></tr><?php endif;?></table></div>
<?php include __DIR__.'/../includes/footer.php';?>
