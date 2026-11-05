<?php
session_start();
session_unset(); session_destroy();
header('Location: /laundrycare/login.php'); exit;
?>
