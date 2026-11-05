<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';
if($_SERVER['REQUEST_METHOD']==='POST'){$name=trim($_POST['service_name']??'');$desc=trim($_POST['description']??'');$price=(float)($_POST['price_per_kg']??0);$active=isset($_POST['is_active']);if($name===''||$price<0){$error='Service name and a non-negative price are required.';}else{$st=$pdo->prepare('INSERT INTO services(service_name,description,price_per_kg,is_active) VALUES(?,?,?,?)');$st->execute([$name,$desc?:null,$price,$active]);header('Location:index.php');exit;}}
$title='Add Service';include __DIR__.'/../includes/header.php';?>
<div class="page-head"><div><h1>Add Service</h1></div></div><?php if(!empty($error)):?><div class="error"><?=htmlspecialchars($error)?></div><?php endif;?>
<div class="card form-card"><form method="post"><div class="form-grid">
<div class="field"><label>Service Name</label><input name="service_name" required></div><div class="field"><label>Price per kg</label><input type="number" step="0.01" min="0" name="price_per_kg" required></div>
<div class="field full"><label>Description</label><textarea name="description" rows="3"></textarea></div>
<div class="field"><label><input type="checkbox" name="is_active" checked> Active</label></div>
</div><br><button class="btn">Add Service</button> <a class="btn secondary" href="index.php">Cancel</a></form></div>
<?php include __DIR__.'/../includes/footer.php';?>
