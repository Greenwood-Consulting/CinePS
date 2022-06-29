<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="description" name="description">
  <meta name="google" content="notranslate" />
  <meta content="Mashup templates have been developped by Orson.io team" name="author">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Disable tap highlight on IE-->
  <meta name="msapplication-tap-highlight" content="no">
  
  <link rel="apple-touch-icon" sizes="180x180" href="./assets/apple-icon-180x180.png">
  <link href="./assets/favicon.ico" rel="icon">

  <title>CinePS</title>  

<link href="./main.3f6952e4.css" rel="stylesheet">
</head>             
<body class="minimal">
<div id="site-border-left"></div>
<div id="site-border-right"></div>
<div id="site-border-top"></div>
<div id="site-border-bottom"></div>
<Add your content of header >
<header>
  
  <nav class="navbar  navbar-fixed-top navbar-inverse">
    <div class="container">
    <?php
    include('header.php') 
  ?>
    </div>
  </nav>

<div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content"-->
              <h1 class="text-warning">Bonjour,<br>
                <span id="typed-strings">
                  <span> <p class="text-warning">Bienvenue sur le site de CinéPS</p></span>    
                </span>
                <span id="typed"></span>
                
              </h1>
              <?php
  

include('common.php');

  
 $jour_aujourdhui = date("D");


 
$deb= new DateTime ("Mon 12:00");
$deb = $deb->modify('-1 week');
$fin = new DateTime("Fri 14:00");
$curdate=new DateTime();
$vote_period=($curdate>=$deb && $curdate <= $fin);

/*$vote_period = false;
$proposition_semaine = false;
$vote_termine_cette_semaine = false;
$connecte = true;
$user_vote= false;
$is_proposeur= true;*/
echo "<a href='historique_film.php'><button type='button' class='btn btn-warning'>Historique</button></a>";
echo '<br/>';
echo '<br/>';



printUserVote($id_current_semaine);
//Proposition comportement 1 : on vient du bouton end_proposition
if(isset($_POST['end_proposition'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
  $requete6 = $bdd->query('UPDATE semaine SET proposition_termine = 1 WHERE id ='.$id_current_semaine);
  echo '<mark>Les propositions ont été faites pour cette semaine</mark>';
}

//Propostion comportement 2 : on vient du bouton new_proposition
if(isset($_POST['new_proposition'])){//si un nouveau film est proposé
  $titre_film = $_POST['titre_film'];
  $ajout_du_lien_imdb = $_POST['lien_imdb'];
  $date = date('Y-m-d');
  $sortie_film = $_POST['date'];
  $ajout_film = $bdd->query("INSERT INTO `film` (`titre`, `date`, `sortie_film`, `imdb`) VALUES ('".$titre_film."','".$date."','".$sortie_film."','".$ajout_du_lien_imdb."')");
  $last_id = $bdd->lastInsertId();
  $ajout_de_proposition = $bdd->query("INSERT INTO `proposition` (`semaine`, `film`,`score`) VALUES ('".$id_current_semaine."','".$last_id."','36')");

  echo '<br/>';
  echo '<br/>';
  echo '<br/>';
  
}
?>
<div class="container-fluid mt-9">
<?php

include('calcul_etat.php');

if($connecte){//l'utilisateur est connecté
  if($vote_period){//nous sommes en période de vote
    if($proposition_semaine){//les propositions ont été faite
      if($vote_termine_cette_semaine){//le vote est terminé
        echo "<h2 class='text-warning'>Résultat du vote</h2><br/>";
        printResultatVote($id_current_semaine);

      }else{//le vote n'est pas terminé
        if($is_proposeur){
          echo '<mark>Le vote n\'est pas terminé vous devez attendre</mark>';
        }else{
          if($current_user_a_vote){//l'user a voté
            echo '<mark>Vous avez déjà voté</mark>';
          }else{//l'user n'a pas voté
            echo'<h2 class="text-warning">Vous devez voter </h2>';
            ?>
            <form method="POST" action="save_vote.php">
            <?php
            $vote = $bdd->query("SELECT id AS proposition_id, film AS film_id FROM proposition WHERE semaine = '".$id_current_semaine."'");
            echo '<mark>Voici la liste des films proposés </mark><br/>';
              echo "<table>";
              while ($film = $vote->fetch()){//tant que $film = $requete 7 on affiche le tableau de vote
                $requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
                $titre_film = $requete6->fetch()['titre'];
                echo '<tr><td><mark>'.$titre_film.'</td><td><input class="text-dark" type="number" name="'.$film['proposition_id'].'" value="0" min="0" max="6">'.'</mark> </td></tr>';
              }
              echo "</table>";
              ?>
              <button type="submit" class="btn btn-warning">Voter</button>
              <button type="submit" name="abstention" class="btn btn-warning">S'abstenir</button> </br>
              <?php
          }
        }
       
      }
    }else{//la proposition n'est pas encore faite
      if($is_proposeur){
        echo '<mark>Les propositions de ne sont pas terminés </mark> <br/><br/>';
      printFilmsProposes($id_current_semaine);
      echo '<br/><br />';
      ?>
      <form method="POST" action="index.php">
      <label> Proposition de films:</label>
      <input type="text" name="titre_film"  placeholder="Titre du film" class="text-dark" />
      <input type="text" name="lien_imdb" placeholder="Lien imdb" class="text-dark"/>
      <input type="number" name="date"  placeholder="Année" class="text-dark" >
      <?php
      echo '<br/><button type="submit" name="new_proposition" class="btn btn-warning">Proposer un nouveau film</button><br/>';
      echo '<button type="submit" name="end_proposition"  class="btn btn-warning">Proposition terminé</button>';
      ?>
      </form>
      <?php
      }else{
        if($proposeur_cette_semaine){
          echo"<mark>Les films n'ont pas été proposé. Cette semaine c'est le tour de " .$proposeur_cette_semaine."</mark>";
        }else{
          echo "<mark>Aucun proposeur n'a encore été défini</mark>";
        }
      
      }
    }
  }else{//nous ne sommes pas en période de vote
    printResultatVote($id_current_semaine);
  }
}else{//aucun utilisateur est connecté
  if($vote_period){//nous sommes en période de vote mais nous ne sommes pas connectés
    if($proposition_semaine){//les propositions ont été faite mais nous ne sommes pas connectés
      if($vote_termine_cette_semaine){//le vote est terminé et pas connecté
        printResultatVote($id_current_semaine);

      }else{//le vote n'est pas terminé mais pas connecté
        printFilmsProposes($id_current_semaine);
      }

    }else{//la proposition n'est pas encore faite et pas connecté
      echo '<mark>la proposition n\'a pas encore été faite</mark>';
    }
  }else{//nous ne sommes pas en période de vote et pas connecté
    printResultatVote($id_current_semaine);
  }
}


 ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
 
 
                
  

  

  <script>
  document.addEventListener("DOMContentLoaded", function (event) {
     type();
     movingBackgroundImage();
  });
</script>
<script type="text/javascript" src="./main.70a66962.js"></script>





  
  
  
</html>