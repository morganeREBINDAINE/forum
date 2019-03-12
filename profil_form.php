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
    <?php include 'parts/header.php';
    include 'parts/fonctions.php';?>

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
      $erreurs=array();
        if(isset($_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['ville'], $_POST['passions'], $_POST['description'], $_POST['mail'], $_FILES['avatar'])){
          $mail=trim(strtolower(htmlspecialchars($_POST['mail'])));
          $nom=trim(ucfirst(strtolower(htmlspecialchars($_POST['nom']))));
          $prenom=trim(ucfirst(strtolower(htmlspecialchars($_POST['prenom']))));
          $date_naissance=trim(htmlspecialchars($_POST['date_naissance']));
          $ville=trim(ucfirst(strtolower(htmlspecialchars($_POST['ville']))));
          $passions=trim(ucfirst(strtolower(htmlspecialchars($_POST['passions']))));
          $description=trim(htmlspecialchars($_POST['description']));

          $a=$_FILES['avatar'];
          $a_nom=$a['name'];
          $a_doss=$a['tmp_name'];
          $a_size=$a['size'];

          $a_xt=strtolower(strrchr($a_nom, '.'));
          $xt_autorise= array('.jpg', '.jpeg', '.png');


          // RECHERCHE DES ERREURS

          if(!is_uploaded_file($a_doss)){
            $erreurs[0]= 'Erreur lors du téléchargement de l\'avatar.';
          }elseif(!in_array($a_xt, $xt_autorise)){
            $erreurs[0]= 'Format acceptés : JPEG et PNG';
          }elseif($a_size > 1048576){
            $erreurs[0]= 'Image trop lourde (max 1Mo)';
          }else{
            list($a_w, $a_h)=getimagesize($a_doss);
            $ratioImg=$a_w/$a_h;
            $wmax=150;
            $hmax=180;
            if($wmax/$hmax > $ratioImg){
              $wmax=$hmax*$ratioImg;
            }else{
              $hmax=$wmax/$ratioImg;
            }

            $src=imagecreatefromstring(file_get_contents($a_doss));
            $min=imagecreatetruecolor($wmax, $hmax);
            $path='min/'.time().'.png';
            imagecopyresampled($min, $src, 0,0,0,0,$wmax,$hmax,$a_w,$a_h);
            imagepng($min, $path);
            $req=$bdd->query('UPDATE profils SET avatar="'.$path.'" WHERE id_profil='.$_SESSION['id']);
          }

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


          // ENVOI EN BDD
          if(empty($erreurs)){
            $req=$bdd->query('UPDATE profils SET prenom="'.$prenom.'", nom="'.$nom.'", date_naissance='.$date_naissance.', ville="'.$ville.'", passions="'.$passions.'", description="'.$description.'" WHERE id_profil='.$_SESSION['id']);
            header('Location:profil.php');
          }
        }
       ?>
      <img src="<?php if(isset($donneesActuelles['avatar'])){echo $donneesActuelles['avatar'];}else{echo 'img/empty.png';} ?>" alt="avatar">
      <form class="form_profil" action="profil_form.php" method="post" enctype="multipart/form-data">
        <label for="avatar">Choisissez un avatar:</label><input type="file" name="avatar" id="avatar">
        <?php erreurs($erreurs, 0); ?>
        <label>Votre prénom :</label><input type="text" name="prenom" placeholder="ex: Walter" value="<?php if(isset($donneesActuelles['prenom'])){echo $donneesActuelles['prenom'];} ?>">
        <?php erreurs($erreurs, 1);?>
        <label>Votre nom :</label><input type="text" name="nom" placeholder="ex: White" value="<?php if(isset($donneesActuelles['nom'])){echo $donneesActuelles['nom'];} ?>">
        <?php erreurs($erreurs, 2); ?>
        <label>Votre date de naissance :</label><input type="text" name="date_naissance" placeholder="ex: 07/09/1959" value="<?php if(isset($donneesActuelles['date_naissance'])){echo $donneesActuelles['date_naissance'];} ?>">
        <?php erreurs($erreurs, 3); ?>
        <label>Votre mail :</label><input type="text" name="mail" placeholder="walter_white@breaking.bad" value="<?php echo $donneesActuelles['mail'];
         ?>" required>
         <?php erreurs($erreurs, 4); ?>
        <label>Votre ville :</label><input type="text" name="ville" placeholder="ex: Albuquerque" value="<?php if(isset($donneesActuelles['ville'])){echo $donneesActuelles['ville'];} ?>">
        <?php erreurs($erreurs, 5); ?>
        <label>Vos centres d'intérêt :</label><textarea name="passions" placeholder="ex: Le travail manuel..."><?php if(isset($donneesActuelles['passions'])){echo $donneesActuelles['passions'];} ?></textarea>
        <?php erreurs($erreurs, 6); ?>
        <label>Une petite description : </label><textarea name="description" placeholder="ex: Je suis un professeur de chimie passionné et impliqué ;)"><?php if(isset($donneesActuelles['description'])){echo $donneesActuelles['description'];} ?></textarea>
        <?php erreurs($erreurs, 7); ?>
        <input type="submit" value="C'est parti !">
      </form>
    </main>
    <?php include 'parts/footer.php'; ?>
  </body>
</html>
