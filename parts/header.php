<?php
  try {
    $bdd= new PDO('mysql:host=localhost;dbname=forum', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  }
  catch(Exception $e) {
    die('erreur ' . $e -> getMessage());
  }
 ?>
<header>
  <div class="logo">
    <a href="forum.php"><img src="img/logo.png" alt="Logo"></a>
    <div>
      <a href="forum.php"><h1>Lorem Ipsum</h1></a>
      <h2>pour les passionnés du Lorem !</h2>
    </div>
  </div>
  <nav>
    <ul>
      <li><a href="forum.php">Forum</a></li>
      <div>
        <?php if(isset($_SESSION['pseudo'])){?>
          <li><a href="profil.php">profil</a></li>
          <?php
        } else { ?>
          <li><a href="inscription.php">inscription</a></li>
          <?php
        }
        if(isset($_SESSION['pseudo'])){?>
          <li><a href="deconnexion.php">déconnexion</a> </li>
          <?php
        }else{?>
          <li><a href="connexion.php">connexion</a> </li>
          <?php
        } ?>
      </div>
    </ul>
  </nav>
</header>
