<?php session_start();
if(!isset($_SESSION['pseudo']) OR !isset($_SESSION['id'])){
  header('Location:index.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php';
    ?>
    <main>
      <h3>PLOT TWIST !</h3>
      <img src="img/lol.png" alt="">
      <p>Il n'existe pas de forum, c'est juste un projet de création d'espace membre !</p>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
