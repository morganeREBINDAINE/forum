<?php
session_start();
$_SESSION=array();
session_destroy();
unset($_SESSION);
setcookie('pseudo', NULL, -1);
setcookie('id', NULL, -1);

header('Location:index.php');
?>
