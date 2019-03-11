<?php
session_start();
if(!isset($_SESSION['pseudo']) OR !isset($_SESSION['id'])){
  header('Location:index.php');
}
 ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <?php include 'parts/head.php'; ?>
  <body>
    <?php include 'parts/header.php'; ?>
    <main>
      <?php
        $req=$bdd->query('SELECT *, DATE_FORMAT(date_naissance, "%d/%m/%Y") as date_naissance
          FROM identifiants
          INNER JOIN profils
          ON identifiants.id=profils.id_profil
          WHERE identifiants.pseudo="'.$_SESSION['pseudo'].'" AND identifiants.id='.$_SESSION['id']);
        $donneesActuelles=$req->fetch();
       ?>
      <h3>Complétez ou modifiez votre profil</h3>
      <?php
        if(isset($_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['ville'], $_POST['passions'], $_POST['description'], $_POST['mail'])){
          $mail=trim(strtolower(htmlspecialchars($_POST['mail'])));
          $nom=trim(ucfirst(strtolower(htmlspecialchars($_POST['nom']))));
          $prenom=trim(ucfirst(strtolower(htmlspecialchars($_POST['prenom']))));
          $date_naissance=trim(htmlspecialchars($_POST['date_naissance']));
          $ville=trim(ucfirst(strtolower(htmlspecialchars($_POST['ville']))));
          $passions=trim(ucfirst(strtolower(htmlspecialchars($_POST['passions']))));
          $description=trim(htmlspecialchars($_POST['description']));
          $erreurs=array();

          // RECHERCHE DES ERREURS

          if(!empty($prenom) AND !preg_match('#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{2,20}$#i', $prenom)){
            $erreurs[1]= 'Prénom invalide<br>';
          }
          if(!empty($nom) AND !preg_match('#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{2,20}$#i', $nom)){
            $erreurs[2]= 'Nom invalide<br>';
          }
          if(!empty($date_naissance)){
              if(!preg_match('#^([0-9]{2}/){2}[0-9]{4}$#', $date_naissance)){
                $erreurs[3]= 'Format souhaité JJ/MM/AAAA';
              }
              else{
                $date_naissance=preg_replace('#/#', '-', $date_naissance);
                list($jour, $mois, $an)=explode('-', $date_naissance);
                if(!checkdate($mois, $jour, $an)){
                  $erreurs[3]= 'Cette date est invalide.';
                }

                if($an<(date('Y')-100)){
                  $erreurs[3]= 'Pas possible dêtre aussi vieux!';
                }
                elseif(strtotime($date_naissance)>strtotime(date('d-m-Y'))){
                  $erreurs[3]= 'Euh... La date entrée est supérieure à aujourd\'hui!';
                }else{
                  $date_naissance= preg_replace('#^([0-9]{2})-([0-9]{2})-([0-9]{4})$#', '"$3-$2-$1"', $date_naissance);
                }
              }
          } else{
            $date_naissance='NULL';
          }
          if(empty($mail)){
            $erreurs[4]= 'Le mail est obligatoire!';
          }elseif(!preg_match('#^[a-z0-9]+[a-z0-9_\.-]*@[a-z0-9_-]+\.[a-z]{2,4}$#i', $mail)){
            $erreurs[4]= 'Le mail est invalide';
          }elseif($mail!=$donneesActuelles['mail']){
            $req=$bdd->query('SELECT * FROM identifiants WHERE mail="'.$mail.'" AND id!='.$_SESSION['id']);
            $donnees=$req->fetch();
            if(!empty($donnees)){
              $erreurs[4]= 'Mail déjà lié à un compte !';
            }
          }

          if(!empty($ville) AND !preg_match('#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{2,25}$#i', $ville)){
            $erreurs[5]= 'Ville invalide<br>';
          }
          if(!empty($passions) AND strlen($passions)>400){
            $erreurs[6]= 'Votre chaine de caractères est trop longue : ' . strlen($passions). '/400 caractères autorisés.';
          }
          if(!empty($description) AND strlen($description)>400){
            $erreurs[7]= 'Votre chaine de caractères est trop longue : ' . strlen($description). '/400 caractères autorisés.';
          }


          // SI LE FORMULAIRE COMPORTE DES ERREURS
          if(!empty($erreurs)){
            $nbErreurs=count($erreurs);
          } else{
            $req=$bdd->query('UPDATE profils SET prenom="'.$prenom.'", nom="'.$nom.'", date_naissance='.$date_naissance.', ville="'.$ville.'", passions="'.$passions.'", description="'.$description.'" WHERE id_profil='.$_SESSION['id']);
            header('Location:profil.php');
          }
        }
       ?>
      <form class="form_profil" action="profil_form.php" method="post">
        <label>Votre prénom :</label><input type="text" name="prenom" placeholder="ex: Walter" value="<?php if(isset($donneesActuelles['prenom'])){echo $donneesActuelles['prenom'];} ?>">
        <?php if(isset($erreurs[1])){echo '<p class="erreurs">' .$erreurs[1].'</p>';} ?>
        <label>Votre nom :</label><input type="text" name="nom" placeholder="ex: White" value="<?php if(isset($donneesActuelles['nom'])){echo $donneesActuelles['nom'];} ?>">
        <?php if(isset($erreurs[2])){echo '<p class="erreurs">' .$erreurs[2].'</p>';} ?>
        <label>Votre date de naissance :</label><input type="text" name="date_naissance" placeholder="ex: 07/09/1959" value="<?php if(isset($donneesActuelles['date_naissance'])){echo $donneesActuelles['date_naissance'];} ?>">
        <?php if(isset($erreurs[3])){echo '<p class="erreurs">' .$erreurs[3].'</p>';} ?>
        <label>Votre mail :</label><input type="text" name="mail" placeholder="walter_white@breaking.bad" value="<?php echo $donneesActuelles['mail'];
         ?>" required>
         <?php if(isset($erreurs[4])){echo '<p class="erreurs">' .$erreurs[4].'</p>';} ?>
        <label>Votre ville :</label><input type="text" name="ville" placeholder="ex: Albuquerque" value="<?php if(isset($donneesActuelles['ville'])){echo $donneesActuelles['ville'];} ?>">
        <?php if(isset($erreurs[5])){echo '<p class="erreurs">' .$erreurs[5].'</p>';} ?>
        <label>Vos centres d'intérêt :</label><textarea name="passions" placeholder="ex: Le travail manuel..."><?php if(isset($donneesActuelles['passions'])){echo $donneesActuelles['passions'];} ?></textarea>
        <?php if(isset($erreurs[6])){echo '<p class="erreurs">' .$erreurs[6].'</p>';} ?>
        <label>Une petite description : </label><textarea name="description" placeholder="ex: Je suis un professeur de chimie passionné et impliqué ;)"><?php if(isset($donneesActuelles['description'])){echo $donneesActuelles['description'];} ?></textarea>
        <?php if(isset($erreurs[7])){echo '<p class="erreurs">' .$erreurs[7].'</p>';} ?>
        <input type="submit" value="C'est parti !">
      </form>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
