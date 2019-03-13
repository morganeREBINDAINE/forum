<?php session_start();
if(!isset($_SESSION['pseudo']) OR !isset($_SESSION['id'])){
  header('Location:index.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php';?>

    <main>
        <?php
      include 'parts/fonctions.php';
      if(isset($_GET['mdp'])AND $_GET['mdp']=='modif'){
        echo 'Votre mot de passe a été modifié.<br><br>';
      }
      $req=$bdd->query('SELECT avatar FROM profils WHERE id_profil='.$_SESSION['id']);
      $donnees=$req->fetch();
      ?>

      <h3>Votre profil</h3>
      <a href="profil_form.php">Complétez ou modifiez vos informations</a>
      <img src="<?php if(isset($donnees['avatar'])){echo $donnees['avatar'];}else{echo 'img/empty.png';} ?>" alt="avatar">
      <div class="infos">
        <h4>Pseudo :</h4>
        <p><?php affiche('pseudo'); ?></p>
      </div>
      <div class="infos">
        <h4>Adresse mail :</h4>
        <p><?php affiche('mail'); ?></p>
      </div>
      <div class="infos">
        <h4>Date d'inscription :</h4>
        <p><?php affiche('date_inscription'); ?></p>
      </div>
      <div class="infos">
        <h4>prénom :</h4>
        <p><?php affiche('prenom'); ?></p>
      </div>
      <div class="infos">
        <h4>nom :</h4>
        <p><?php affiche('nom'); ?></p>
      </div>
      <div class="infos">
        <h4>Date de naissance :</h4>
        <p><?php affiche('date_naissance'); ?></p>
      </div>
      <div class="infos">
        <h4>âge :</h4>
        <p><?php affiche('age'); ?> ans</p>
      </div>
      <div class="infos">
        <h4>Ville :</h4>
        <p><?php affiche('ville'); ?></p>
      </div>
      <div class="infos">
        <h4>Centres d'intérêt :</h4>
        <p><?php affiche('passions'); ?></p>
      </div>
      <div class="infos">
        <h4>Description :</h4>
        <p><?php affiche('description'); ?></p>
      </div>
      <br>
      <a href="profil_mdp.php">Modifiez votre mot de passe ou supprimez votre compte</a>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
