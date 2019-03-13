<?php
function affiche($valeur){
  $bdd= new PDO('mysql:host=localhost;dbname=forum', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  if($valeur!='date_inscription' AND $valeur!='date_naissance' AND $valeur!='age'){
    $req=$bdd->query('SELECT '.$valeur.' FROM profils p INNER JOIN identifiants i ON i.id=p.id_profil WHERE pseudo="'.$_SESSION['pseudo'].'"');
    $donnees=$req->fetch();
    if(!empty($donnees[$valeur])){
      echo $donnees[$valeur];
    } else{
      echo 'non renseigné';
    }
  }elseif($valeur=='date_inscription' OR $valeur=='date_naissance'){
    $req=$bdd->query('SELECT DATE_FORMAT('.$valeur.', "%d/%m/%Y") AS query FROM profils p INNER JOIN identifiants i ON i.id=p.id_profil WHERE pseudo="'.$_SESSION['pseudo'].'"');
    $donnees=$req->fetch();
    if(!empty($donnees['query'])){
      echo $donnees['query'];
    } else{
      echo 'non renseigné';
    }
  } elseif($valeur=="age"){
    $req=$bdd->query('SELECT TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) as age FROM profils WHERE id_profil="'.$_SESSION['id'].'"');
    $donnees=$req->fetch();
    if(empty( $donnees['age'])){
      echo 'non renseigné';
    }else {
       echo $donnees['age'];
    }
  }
}

// FONCTION ERREURS
function erreurs($array, $num){
  if(isset($array[$num])){
    echo '<p class="erreurs">' .$array[$num].'</p>';
  }
}

 ?>
