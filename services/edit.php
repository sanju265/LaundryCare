<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';$id=(int)($_GET['id']??0);$st=$pdo->prepare('SELECT * FROM services WHERE id=?');$st->execute([$id]);$row=$st->fetch();if(!$row)die('Service not found');
if($_SERVER['REQUEST_METHOD']==='POST'){$name=trim($_POST['service_name']??'');$desc=trim($_POST['description']??'');$price=(float)($_POST['price_per_kg']??0);$active=isset($_POST['is_active']);if($name===''||$price<0){$error='Service name and a non-negative price are required.';}else{$st=$pdo->prepare('UPDATE services SET service_name=?,description=?,price_per_kg=?,is_active=? WHERE id=?');$st->execute([$name,$desc?:null,$price,$active,$id]);header('Location:index.php');exit;}}
$title='Edit Service';include __DIR__.'/../includes/header.php';?>
<div class="page-head"><div><h1>Edit Service</h1></div></div><?php if(!empty($error)):?><div class="error"><?=htmlspecialchars($error)?></div><?php endif;?>
<div class="card form-card"><form method="post"><div class="form-grid">
<div class="field"><label>Service Name</label><input name="service_name" value="<?=htmlspecialchars($row['service_name'])?>" required></div><div class="field"><label>Price per kg</label><input type="number" step="0.01" min="0" name="price_per_kg" value="<?=htmlspecialchars($row['price_per_kg'])?>" required></div>
<div class="field full"><label>Description</label><textarea name="description" rows="3"><?=htmlspecialchars($row['description']??'')?></textarea></div>
<div class="field"><label><input type="checkbox" name="is_active" <?=$row['is_active']?'checked':''?>> Active</label></div>
</div><br><button class="btn">Update Service</button> <a class="btn secondary" href="index.php">Cancel</a></form></div>
<?php include __DIR__.'/../includes/footer.php';?>
