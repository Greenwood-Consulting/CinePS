<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="description" name="description">
  <meta name="google" content="notranslate" />
  <meta content="Mashup templates have been developped by Orson.io team" name="author">

  <!-- Disable tap highlight on IE-->
  <meta name="msapplication-tap-highlight" content="no">
  
  <link rel="apple-touch-icon" sizes="180x180" href="./assets/apple-icon-180x180.png">
  <link href="./assets/favicon.ico" rel="icon">

  <title>CinePS</title>  

<!--link href="./main.3f6952e4.css" rel="stylesheet">
</head>             
<body class="minimal">
<div id="site-border-left"></div>
<div id="site-border-right"></div>
<div id="site-border-top"></div>
<div id="site-border-bottom"></div>
<Add your content of header -->
<header>
  
  <nav class="navbar  navbar-fixed-top navbar-inverse">
    <div class="container">
    <?php
    include('header.php') 
  ?>
        <!--button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav ">
          <li><a href="./index.php" title="">01 : Accueil</a></li>
          <li><a href="./propose_film.php" title="">02 : Proposition de films</a></li>
          <li><a href="./vote.php" title="">03 : Résultat vote</a></li>
          <li><a href="./contact.html" title="">04 : Contact</a></li>
          <li><a href="./components.html" title="">05 : Components</a></li>
        </ul>

   
    </div>
  </nav>

<div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content"-->
              <h1>Bonjour,<br>
                <span id="typed-strings">
                  <span>Bienvenue sur le site de CinéPS</span>    
                </span>
                <span id="typed"></span>
                
              </h1>
              <?php
  
  
  if(isset($_SESSION['user'])){//si l'utilisateur est connecté
      echo "<a href='propose_film.php'><button type='button' class='btn btn-warning'>Ajouter un nouveau film</button></a>";}
      ?>
  
  
  
  <?php
  
 $jour_aujourdhui = date("D");
 $heure_aujourdhui = date("H:i:s");
 echo "<br/>";
 echo "$jour_aujourdhui " ;
 echo $heure_aujourdhui. "<br/>";


 
$deb= new DateTime ("Fri 12:00");
$fin = new DateTime("Sat 12:00");
$curdate=new DateTime();
$vote_period=$curdate>=$deb && $curdate <= $fin;

echo $deb->format('m-d-Y H:i:s')."<br/>";
echo $fin->format('m-d-Y H:i:s')."<br/>";


 
$vote_periode = $jour_aujourdhui == "Fri";
echo "<br/>";


$connecte = isset($_SESSION['user']);
echo "<br/>"; 


?>
<a href='propose_film.php'>Propostions</a>
<?php

include('common.php');
$requete1 = $bdd->query("SELECT id FROM proposition WHERE id = '".$id_current_semaine."'");
$proposition_semaine =  $requete1->fetch();
if($proposition_semaine){//si une proposition est faite
  echo 'Il y a deja eu des propositions cette semaine';
}else{//si elle n'est pas faite
  echo "il n'y a pas encore de proposition";
}

$requete2 = $bdd->query("SELECT COUNT(*) AS nb_votes_current_semaine FROM a_vote WHERE semaine = '".$id_current_semaine."'");
$nb_votes = $requete2->fetch()['nb_votes_current_semaine'];
echo '<br/>';

$requete3 = $bdd->query("SELECT COUNT(*) AS nb_personne FROM membre");
$nb_personnes = $requete3->fetch()['nb_personne'];
echo '<br/>';
$vote_termine_cette_semaine = ($nb_personnes == $nb_votes);

if($vote_termine_cette_semaine){//le vote est fini
  echo 'Le vote est terminé';
}else{//le vote n'est pas fini
  echo 'Le vote n est pas terminé <br/>';
}
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur a voté
  $user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
  $id_utlisateur_connecte = $user->fetch()['id'];
  $requete4= $bdd->query("SELECT COUNT(votant) AS a_vote_current_user_semaine FROM a_vote WHERE (id = '".$id_utlisateur_connecte. "' AND semaine = '".$id_current_semaine."')");
  $current_user_a_vote=$requete4->fetch()['a_vote_current_user_semaine']==1;
}

$vote_period = true;
$proposition_semaine = true;
$vote_termine_cette_semaine = false;
$connecte = true;
$user_vote= false;

echo '<br/>';
echo '<br/>';
echo '<br/>';
echo 'Page d\'accueil :';
echo '<br/>';
echo '<br/>';

if($connecte){//l'utilisateur est connecté
  if($vote_period){//nous sommes en période de vote
    if($proposition_semaine){//les propositions ont été faite
      if($vote_termine_cette_semaine){//le vote est terminé
        printResultatVote($id_current_semaine);

      }else{//le vote n'est pas terminé
        if($user_vote){//l'user a voté
          echo 'Vous avez déjà voté';
        }else{//l'user n'a pas voté
          echo'Vous devez voter <br/>';
          ?>
          <form method="POST" action="save_vote.php">
          <?php
        $vote = $bdd->query("SELECT id AS proposition_id, film AS film_id FROM proposition WHERE semaine = '".$id_current_semaine."'");
          echo 'Voici la liste des films proposés <br/>';
            while ($film = $vote->fetch()){//tant que $film = $requete 7 on affiche le tableau de vote
              $requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
              $titre_film = $requete6->fetch()['titre'];
              echo $titre_film.'<input type="number" name="'.$film['proposition_id'].'" value="0" min="1" max="6">'."<br/>";
            }
            ?>
            <button type="submit">Voter</button>
            <?php
        }
      }
    }else{//la proposition n'est pas encore faite
      echo "la proposition n'est pas encore faite";
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
      echo 'la proposition n\'a pas encore été faite';
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
 
 
                
  

  

  <script>
  document.addEventListener("DOMContentLoaded", function (event) {
     type();
     movingBackgroundImage();
  });
</script>
<script type="text/javascript" src="./main.70a66962.js"></script>





  
  
  
</html>