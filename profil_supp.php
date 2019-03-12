<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php'; ?>
    <main class="profil_supp">
      <?php if(!isset($_POST['oui'])){
        ?>
        <img src="img/sad.png" alt="Vous nous quittez?">
        <form action="profil_supp.php" method="post">
          <p>Voulez-vous vraiment supprimer votre compte ?</p>
          <input type="submit" name="oui" value="Oui...">
        </form>
        <?php
      } else { ?>
        <img src="img/sad2.png" alt="Vous nous manquerez...">
        <form action="profil_supp.php" method="post">
          <p>En êtes-vous vraiment certain?</p>
          <input type="submit" name="maisOui" value="MAIS OUI !">
        </form>
      <?php }
      if(isset($_POST['maisOui'])){
        $req=$bdd->query('DELETE FROM identifiants WHERE id='.$_SESSION['id']);
        $req=$bdd->query('DELETE FROM profils WHERE id_profil='.$_SESSION['id']);

        header('Location:deconnexion.php?s=1');
      } ?>

    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
