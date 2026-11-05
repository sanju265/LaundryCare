<?php
require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../config/database.php';
$id=(int)($_GET['id']??0);$st=$pdo->prepare('SELECT * FROM customers WHERE id=?');$st->execute([$id]);$row=$st->fetch();if(!$row)die('Customer not found');
if($_SERVER['REQUEST_METHOD']==='POST'){ $name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$email=trim($_POST['email']??''); if($name===''||$phone===''){$error='Name and phone are required.';}else{$st=$pdo->prepare('UPDATE customers SET name=?,phone=?,email=? WHERE id=?');$st->execute([$name,$phone,$email?:null,$id]);header('Location:index.php');exit;}}
$title='Edit Customer';include __DIR__.'/../includes/header.php';?>
<div class="page-head"><div><h1>Edit Customer</h1></div></div>
<?php if(!empty($error)):?><div class="error"><?=htmlspecialchars($error)?></div><?php endif;?>
<div class="card form-card"><form method="post"><div class="form-grid">
<div class="field"><label>Full Name</label><input name="name" value="<?=htmlspecialchars($row['name'])?>" required></div>
<div class="field"><label>Phone</label><input name="phone" value="<?=htmlspecialchars($row['phone'])?>" required></div>
<div class="field full"><label>Email</label><input type="email" name="email" value="<?=htmlspecialchars($row['email']??'')?>"></div>
</div><br><button class="btn">Update Customer</button> <a class="btn secondary" href="index.php">Cancel</a></form></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
