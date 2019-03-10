<?php session_start();
if(isset($_SESSION['id']) OR isset($_SESSION['pseudo'])){
  header('Location:profil.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php'; ?>
    <main>
      <h3>Connexion</h3>
      <?php

    if(isset($_COOKIE['pseudo'], $_COOKIE['mdp'])){
      $pseudo_cookie=htmlspecialchars($_COOKIE['pseudo']);
      $mdp_cookie=htmlspecialchars($_COOKIE['mdp']);

      $req=$bdd->query('SELECT pseudo, mdp FROM identifiants WHERE pseudo="'.$pseudo_cookie.'"');
      $donnees=$req->fetch();
      $hash=$donnees['mdp'];

      if(!empty($donnees) AND password_verify($mdp_cookie, $hash)){
        $req=$bdd->query('SELECT id FROM identifiants WHERE pseudo="'.$pseudo.'" AND mdp="'.$hash.'"');
        $donnees=$req->fetch();
        $_SESSION['id'] = $donnees['id'];
        $_SESSION['pseudo'] = $pseudo_cookie;
        header('Location:profil.php');
      }
      $req->closeCursor();

    }
    if(isset($_POST['pseudo']) AND isset($_POST['mdp'])){
      $pseudo=trim(htmlspecialchars($_POST['pseudo']));
      $mdp=htmlspecialchars($_POST['mdp']);


      if(!empty($pseudo) OR !empty($mdp)){
        $req=$bdd->query('SELECT mdp FROM identifiants WHERE pseudo="'.$pseudo.'"');
        $donnees=$req->fetch();
        $hash=$donnees['mdp'];

        if(empty($donnees) OR !password_verify($mdp, $hash)){
          $erreurs[1]= 'Pseudo ou mot de passe incorrect !';
        } else{
          //verifier connexion auto
          if(isset($_POST['connexion_auto'])){
            setcookie('pseudo', $pseudo, time() + 365*24*3600, null, null, false, true);
            setcookie('mdp', $mdp, time() + 365*24*3600, null, null, false, true);
            $req=$bdd->query('SELECT id FROM identifiants WHERE pseudo="'.$pseudo.'" AND mdp="'.$hash.'"');
            $donnees=$req->fetch();
            $_SESSION['id'] = $donnees['id'];
            $_SESSION['pseudo']=$pseudo;
            $req->closeCursor();
            header('Location: profil.php');
          } else{
            $req=$bdd->query('SELECT id FROM identifiants WHERE pseudo="'.$pseudo.'" AND mdp="'.$hash.'"');
            $donnees=$req->fetch();
            $_SESSION['id'] = $donnees['id'];
            $_SESSION['pseudo']=$pseudo;
            $req->closeCursor();
            header('Location: profil.php');
          }
        }
        $req->closeCursor();
      } else {
        $erreurs[1]= 'Merci de remplir les 2 champs !';
      }
    }



     ?>
      <p>Pour vous connecter, merci de renseigner vos identifiants ! <i class="far fa-smile-wink"></i></p>
      <form action="connexion.php" method="post">
        <input type="text" name="pseudo" placeholder="Votre pseudo">
        <input type="password" name="mdp" placeholder="Votre mot de passe">
        <?php if(!empty($erreurs[1])){echo '<p class="erreurs">' .$erreurs[1]. '</p>';} ?>
        <div>
          <label for="connexion_auto">Connexion automatique : </label>
          <input type="checkbox" name="connexion_auto" id="connexion_auto">
        </div>
        <input type="submit" name="" value="Se connecter!">
      </form>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
