<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php'; ?>
    <main class="index">
      <?php if(isset($_GET['s']) AND $_GET['s']==1){
        echo 'Votre compte a bien été supprimé.<br>';
      } ?>
      <span>Bienvenue !<img src="img/bulle.png"> </span>
      <?php if(!isset($_SESSION['pseudo'])){ ?>
      <p>Pour utiliser ce forum, <a href="inscription.php">inscrivez-vous</a> ou <a href="connexion.php">authentifiez-vous</a>!</p> <?php }
      else{
        echo '<p>Cliquez <a href="forum.php">ici</a> pour accéder au forum !</p>';
      }?>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
