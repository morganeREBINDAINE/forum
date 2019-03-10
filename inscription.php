<?php session_start();
if(isset($_SESSION['pseudo'])OR isset($_SESSION['id'])){
  header('Location:profil.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php'; ?>
    <main>
      <?php
      if(isset($_POST['pseudo'], $_POST['mdp'], $_POST['mail'], $_POST['mdp_confirm'])){
        $pseudo=trim(htmlspecialchars($_POST['pseudo']));
        $mdp=htmlspecialchars($_POST['mdp']);
        $mdp_confirm=htmlspecialchars($_POST['mdp_confirm']);
        $mail=trim(htmlspecialchars($_POST['mail']));
        $erreurs=array();

        //Recherche si le pseudo existe/erreurs pseudo
        $req=$bdd->query('SELECT pseudo FROM identifiants WHERE pseudo="'.$pseudo.'"');
        $donnees=$req->fetch();

        if(!preg_match('#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{4,}$#i', $pseudo)){
          $erreurs[1]= 'Pseudo invalide (4 caractères minimum)<br>';
        } elseif(!empty($donnees)){
          $erreurs[1]= 'Ce pseudo est déjà utilisé';
        }

        //recherche erreurs mdp
        if(!preg_match('#^.{5,}$#', $mdp)){
          $erreurs[2]= 'Mot de passe invalide ou non renseigné<br>';
        }
        elseif(empty($mdp_confirm)){
          $erreurs[3]= 'Merci de confirmer le mot de passe.';
        }
        elseif($mdp!==$mdp_confirm){
          $erreurs[3]= 'Mots de passes différents<br>';
        }
        elseif(!empty($mdp) AND !empty($pseudo) AND $mdp==$pseudo){
          $erreurs[2]= 'Le mot de passe ne doit pas être le même que le pseudo!';
        }

        //recherche si le mail existe/erreurs mail
        $req=$bdd->query('SELECT mail FROM identifiants WHERE mail="'.$mail.'"');
        $donnees=$req->fetch();
        if(!empty($donnees)){
          $erreurs[4]= 'Ce mail est déjà utilisé';
        }
        elseif(!preg_match('#^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]{2,}\.[a-zA-Z]{2,4}$#', $mail)) {
          $erreurs[4]= 'Mail invalide ou non renseigné<br>';
        }
        $req->closeCursor();



        if(empty($erreurs)) {
          $mdpHache=password_hash($mdp, PASSWORD_DEFAULT);
          $req=$bdd->query('INSERT INTO identifiants(pseudo, mdp, mail, date_inscription) VALUES("'.$pseudo.'", "'.$mdpHache.'", "'.$mail.'", NOW())');
          $req=$bdd->query('SELECT id FROM identifiants WHERE pseudo="'.$pseudo.'" AND mdp="'.$mdpHache.'"');
          $donnees=$req->fetch();
          $newId=$donnees['id'];
          $req=$bdd->query('INSERT INTO profils(id_profil) VALUES('.$newId.') ');
          $req->closeCursor();
          $req=$bdd->query('SELECT id FROM identifiants WHERE pseudo="'.$pseudo.'" AND mail="'.$mail.'"');
          $donnees=$req->fetch();
          $_SESSION['pseudo']=$pseudo;
          $_SESSION['id']=$donnees['id'];
          $req->closeCursor();
          header('Location:profil.php');
        }
      }
      // include 'parts/fonctions.php';
       ?>
      <h3>Inscription</h3>
        <p>Pour vous inscrire, merci de remplir ce (court) formulaire!</p>
      <form action="inscription.php" method="post">
        <input type="text" name="pseudo" placeholder="Pseudo souhaité" >
        <?php if(isset($erreurs[1])){echo '<p class="erreurs">' .$erreurs[1].'</p>';} ?>
        <input type="password" name="mdp" placeholder="Mot de passe" >
        <?php if(isset($erreurs[2])){echo '<p class="erreurs">' .$erreurs[2].'</p>';} ?>
        <input type="password" name="mdp_confirm" placeholder="Confirmez mot de passe" >
        <?php if(isset($erreurs[3])){echo '<p class="erreurs">' .$erreurs[3].'</p>';} ?>
        <input type="text" name="mail" placeholder="Votre mail" >
        <?php if(isset($erreurs[4])){echo '<p class="erreurs">' .$erreurs[4].'</p>';} ?>

        <input type="submit" value="C'est parti !">
      </form>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
