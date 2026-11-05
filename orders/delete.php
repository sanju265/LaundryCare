<?php
require_once __DIR__ . '/../includes/auth.php';require_once __DIR__ . '/../config/database.php';$id=(int)($_GET['id']??0);$st=$pdo->prepare('DELETE FROM orders WHERE id=?');$st->execute([$id]);header('Location:index.php');exit;
?>
