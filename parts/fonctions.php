<?php
function affiche($valeur){
  $bdd= new PDO('mysql:host=localhost;dbname=forum', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  if($valeur!='date_inscription' AND $valeur!='date_naissance'){
    $req=$bdd->query('SELECT '.$valeur.' FROM profils p INNER JOIN identifiants i ON i.id=p.id_profil WHERE pseudo="'.$_SESSION['pseudo'].'"');
    $donnees=$req->fetch();
    if(!empty($donnees[$valeur])){
      echo $donnees[$valeur];
    } else{
      echo 'non renseigné';
    }
  }else{
    $req=$bdd->query('SELECT DATE_FORMAT('.$valeur.', "%d/%m/%Y") AS query FROM profils p INNER JOIN identifiants i ON i.id=p.id_profil WHERE pseudo="'.$_SESSION['pseudo'].'"');
    $donnees=$req->fetch();
    if(!empty($donnees['query'])){
      echo $donnees['query'];
    } else{
      echo 'non renseigné';
    }
  }
}

 ?>
