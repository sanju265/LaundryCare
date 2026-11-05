<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';$id=(int)($_GET['id']??0);
try{$st=$pdo->prepare('DELETE FROM services WHERE id=?');$st->execute([$id]);header('Location:index.php');exit;}catch(PDOException $e){die('Cannot delete this service because an order references it. Delete/update the related orders first.');}
?>
