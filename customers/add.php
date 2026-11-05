<?php
require_once __DIR__ . '/../includes/auth.php'; require_once __DIR__ . '/../config/database.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
$name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$email=trim($_POST['email']??'');
if($name===''||$phone===''){$error='Name and phone are required.';}else{$st=$pdo->prepare('INSERT INTO customers(name,phone,email) VALUES(?,?,?)');$st->execute([$name,$phone,$email?:null]);header('Location: index.php');exit;}
}
$title='Add Customer'; include __DIR__.'/../includes/header.php'; ?>
<div class="page-head"><div><h1>Add Customer</h1></div></div>
<?php if(!empty($error)):?><div class="error"><?=htmlspecialchars($error)?></div><?php endif;?>
<div class="card form-card"><form method="post"><div class="form-grid">
<div class="field"><label>Full Name</label><input name="name" required></div>
<div class="field"><label>Phone</label><input name="phone" required></div>
<div class="field full"><label>Email</label><input type="email" name="email"></div>
</div><br><button class="btn">Add Customer</button> <a class="btn secondary" href="index.php">Cancel</a></form></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
