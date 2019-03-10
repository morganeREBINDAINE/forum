<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php'; ?>
    <main>
      <?php
      if(isset($_POST['mdp_actuel'], $_POST['mdp_nv'], $_POST['mdp_confirm'])){
        $mdp_actuel=htmlspecialchars($_POST['mdp_actuel']);
        $mdp_nv=htmlspecialchars($_POST['mdp_nv']);
        $mdp_confirm=htmlspecialchars($_POST['mdp_confirm']);
        $erreurs=array();

        $req=$bdd->query('SELECT mdp, pseudo FROM identifiants WHERE id='.$_SESSION['id']);
        $donnees=$req->fetch();
        $hash=$donnees['mdp'];
        $pseudo=$donnees['pseudo'];

        if(empty($mdp_actuel) OR empty($mdp_nv) OR empty($mdp_confirm)){
          $erreurs[1]= 'Veuillez remplir les 3 champs !';
        } elseif(!password_verify($mdp_actuel, $hash)){
          $erreurs[2]= 'Ce n\'est pas le bon mot de passe!';
        }elseif($mdp_nv!=$mdp_confirm){
          $erreurs[1] = 'Vous n\'avez pas entré le même mot de passe!';
        }elseif(!preg_match('#^.{5,}$#', $mdp_nv)){
          $erreurs[1]= 'Le mot de passe doit contenir au moins 5 caractères.';
        }elseif($mdp_nv==$pseudo){
        $erreurs[1]= 'Le mot de passe doit être différent du pseudo.';
        }
        else{
          $mdp_nv=password_hash($mdp_nv, PASSWORD_DEFAULT);
          $req=$bdd->query('UPDATE identifiants SET mdp="'.$mdp_nv.'" WHERE id='.$_SESSION['id']);
          header('Location:profil.php?mdp=modif');
        }
      }
       ?>
      <h3>Gérer le compte</h3>
      <form class="form_profil" action="profil_mdp.php" method="post">
        <label>Entrez votre mot de passe actuel :</label>
        <input type="password" name="mdp_actuel" value="">
        <?php if(!empty($erreurs['2'])){echo '<p class="erreurs">'.$erreurs[2].'</p>';} ?>
        <label>Entrez le nouveau mot de passe :</label>
        <input type="password" name="mdp_nv">
        <label>Confirmez le nouveau mot de passe :</label>
        <input type="password" name="mdp_confirm">
        <?php if(!empty($erreurs['1'])){echo '<p class="erreurs">'.$erreurs[1].'</p>';} ?>
        <input type="submit" value="Modifier le mot de passe">
      </form>
      <a href="profil_supp.php">Supprimer le compte</a>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
