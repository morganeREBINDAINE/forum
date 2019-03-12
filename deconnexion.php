<?php
session_start();
$_SESSION=array();
session_destroy();
unset($_SESSION);
setcookie('pseudo', NULL, -1);
setcookie('id', NULL, -1);

if(isset($_GET['s'])AND $_GET['s']==1){
  header('Location:index.php?s=1');
}

header('Location:index.php');
?>
