<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';$id=(int)($_GET['id']??0);$st=$pdo->prepare('SELECT * FROM orders WHERE id=?');$st->execute([$id]);$row=$st->fetch();if(!$row)die('Order not found');
$customers=$pdo->query('SELECT id,name FROM customers ORDER BY name')->fetchAll();$services=$pdo->query('SELECT id,service_name,price_per_kg FROM services ORDER BY service_name')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){$customer=(int)($_POST['customer_id']??0);$service=(int)($_POST['service_id']??0);$weight=(float)($_POST['weight']??0);$due=$_POST['due_date']??'';$status=$_POST['status']??'Received';$st=$pdo->prepare('SELECT price_per_kg FROM services WHERE id=?');$st->execute([$service]);$price=$st->fetchColumn();if(!$customer||$price===false||$weight<=0||$due===''){$error='Please enter valid values.';}else{$total=$price*$weight;$st=$pdo->prepare('UPDATE orders SET customer_id=?,service_id=?,weight=?,due_date=?,total_amount=?,status=? WHERE id=?');$st->execute([$customer,$service,$weight,$due,$total,$status,$id]);header('Location:index.php');exit;}}
$title='Edit Order';include __DIR__.'/../includes/header.php';?>
<div class="page-head"><div><h1>Edit Order</h1></div></div><?php if(!empty($error)):?><div class="error"><?=htmlspecialchars($error)?></div><?php endif;?>
<div class="card form-card"><form method="post"><div class="form-grid">
<div class="field"><label>Customer</label><select name="customer_id" required><?php foreach($customers as $c):?><option value="<?=$c['id']?>" <?=$row['customer_id']==$c['id']?'selected':''?>><?=htmlspecialchars($c['name'])?></option><?php endforeach;?></select></div>
<div class="field"><label>Service</label><select name="service_id" required><?php foreach($services as $s):?><option value="<?=$s['id']?>" <?=$row['service_id']==$s['id']?'selected':''?>><?=htmlspecialchars($s['service_name'])?> - ₹<?=$s['price_per_kg']?>/kg</option><?php endforeach;?></select></div>
<div class="field"><label>Weight (kg)</label><input type="number" step="0.01" min="0.01" name="weight" value="<?=htmlspecialchars($row['weight'])?>" required></div>
<div class="field"><label>Due Date</label><input type="date" name="due_date" value="<?=htmlspecialchars($row['due_date'])?>" required></div>
<div class="field"><label>Status</label><select name="status"><?php foreach(['Received','Washing','Ready','Delivered'] as $s):?><option <?=$row['status']===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select></div>
</div><br><button class="btn">Update Order</button> <a class="btn secondary" href="index.php">Cancel</a></form></div>
<?php include __DIR__.'/../includes/footer.php';?>
