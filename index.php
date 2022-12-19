<!DOCTYPE html>
<html lang="en">

<?php
include('common.php');
// calcul de la date de fin de la période de vote
$fin_periode_vote = new DateTime("Fri 16:26");
$fin_periode_vote = $fin_periode_vote->format('Y-m-d H:i:s');

// conversion de la date de fin en timestamp JavaScript
$deadline_vote = strtotime($fin_periode_vote);
$deadline_vote = $deadline_vote*1000;
?>



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

  <!--Gestion du compte à rebours de la période de vote -->
<script>
// Injection de la date de fin PHP dans une variable Javascript
var deadline_vote = <?php echo $deadline_vote; ?>;

var x = setInterval(function() {
    var now = new Date().getTime();
    var t = deadline_vote - now;
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((t % (1000 * 60)) / 1000);
    document.getElementById("demo").innerHTML = days + "d " 
        + hours + "h " + minutes + "m " + seconds + "s ";
    if (t < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "";
    }
}, 1000);
</script>

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
            <div class="text-content">
              <h1 class="text-warning">CinePS</h1>
              <?php
  


  
 $jour_aujourdhui = date("D");


 
$deb= new DateTime ("Mon 12:00");
$deb = $deb->modify('-1 week');
$fin = new DateTime("Fri 18:00");
$curdate=new DateTime();
$vote_period=($curdate>=$deb && $curdate <= $fin);




printUserVote($id_current_semaine);


//Proposition comportement 1 : on vient du bouton end_proposition
if(isset($_POST['end_proposition'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
  $update_status_proposition= $bdd->prepare('UPDATE semaine SET proposition_termine = 1 WHERE id = ?');
  $update_status_proposition->execute([$id_current_semaine]);
  echo '<mark>Les propositions ont été faites pour cette semaine</mark>';
  /*$to = $requete_mail;

  $subject = 'Mail envoyé depuis un script PHP';

  $message = 'Allez Paris!';

  $headers = 'From: cineps@gc2.fr'       . "\r\n" .
             'Reply-To: cineps@gc2.fr' . "\r\n" .
             'X-Mailer: PHP/' . phpversion();

  mail($to, $subject, $message, $headers);*/


}


//Propostion comportement 2 : on vient du bouton new_proposition
if(isset($_POST['new_proposition'])){//si un nouveau film est proposé
  //$titre_film = $bdd->quote($_POST['titre_film']);
  $titre_film = addslashes($_POST['titre_film']);
  $ajout_du_lien_imdb = addslashes($_POST['lien_imdb']);
  $date = date('Y-m-d');
  $sortie_film = addslashes($_POST['date']);    
  $ajout_film = $bdd->prepare("INSERT INTO `film` (`titre`, `date`, `sortie_film`, `imdb`) VALUES (?,?,?,?)");
  $ajout_film->execute([$titre_film, $date, $sortie_film, $ajout_du_lien_imdb]);
  $last_id = $bdd->lastInsertId();
  $ajout_de_proposition = $bdd->prepare("INSERT INTO `proposition` (`semaine`, `film`,`score`) VALUES (?, ? , ?)  ");
  $ajout_de_proposition->execute([$id_current_semaine, $last_id, 36]);
  
  echo '<br/>';
  echo '<br/>';
  echo '<br/>';
}

if(isset($_POST['new_theme'])){//si on valide le theme

$theme_film = addslashes($_POST['theme_film']);
$update_theme = $bdd->prepare("UPDATE semaine SET theme = '".$theme_film."'  WHERE id = ?");
$update_theme->execute([$id_current_semaine]);
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
        echo "<a href='resultat_vote.php'><button type='button' class='btn btn-warning'>Résultat vote</button></a>";
        /*printChoixvote($id_current_semaine);*/

      }else{//le vote n'est pas terminé
        //echo '<mark>Compte a rebours avant la fin du vote : <b><div class = "text-warning" id  = "demo"></div></mark></b>';
        if($is_proposeur){//si l'user est proposeur
          echo '<mark>Le vote n\'est pas terminé vous devez attendre</mark>';
        }else{//sinon il ne l'est pas
          if($current_user_a_vote){//l'user a voté
            echo '<mark>Vous avez déjà voté</mark>';
          }else{//l'user n'a pas voté
            echo'<h2 class="text-warning">Vous devez voter </h2>';
            echo "<br />";
            echo '<h2 class="text-warning">Il vous reste <div id="demo"></div> avant la fin du vote</h2>';
            echo '<p class = "text-warning"><b>*Le vote se fait sous forme de classement, par exemple le film que vous préférez voir devra avoir "1" comme vote</b></p>';
            ?>
            <form method="POST" action="save_vote.php">
            <?php
            $vote = $bdd->prepare("SELECT id AS proposition_id, film AS film_id FROM proposition WHERE semaine = ?");
            $vote->execute([$id_current_semaine]);
              echo "<table>";
              while ($film = $vote->fetch()){//on affiche le tableau de vote
                $get_titre_imdb_film = $bdd->prepare('SELECT titre, imdb FROM film WHERE id = ?');
                $get_titre_imdb_film->execute([$film['film_id']]);
                $titre_imdb_film = $get_titre_imdb_film->fetch();
                
                echo '<tr><td><mark><a class="text-dark" href = '.$titre_imdb_film['imdb'].'>' .$titre_imdb_film['titre'].' </a></td><td><input class="text-dark" type="number" name="'.$film['proposition_id'].'" value="0" min="0" max="6">'.'</mark> </td></tr>';
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
      if($is_proposeur){//on affiche la liste des films pour le proposeurs quand il n'a pas terminé la proposition
        
          echo '<mark>Les propositions de ne sont pas terminés </mark> <br/><br/>';
          printFilmsProposes($id_current_semaine);
          echo '<br/><br />';
          ?>
          <form method="POST" action="index.php">
          <label> Proposition de films:</label>
          <?php
          if($etat_theme_non_propose){//si le thème n'est pas rentrer on affiche le formulaire
            echo '<input type="text" name="theme_film" placeholder="Thème film" class="text-dark"/>
                  <button type="submit" name="new_theme" class="btn btn-warning">Choisissez un thème</button><br/><br/>';
            
          }
          ?>
          
          <input type="text" name="titre_film"  placeholder="Titre du film" class="text-dark" />
          <input type="text" name="lien_imdb" placeholder="Lien imdb" class="text-dark"/>
          <input type="number" name="date"  placeholder="Année" class="text-dark" >
          
          <?php
          echo '<button type="submit" name="new_proposition" class="btn btn-warning">Proposer</button><br/>';
          echo '<button type="submit" name="end_proposition"  class="btn btn-warning">Valider les Propositions</button>';
          ?>
          </form>
          <?php
      }else{//sinon les autres users sont informés que le proposeur n'a pas terminé ses propositions
        if($proposeur_cette_semaine){//Si il y a un proposeur défini on affiche qui c'est
          echo"<mark>Les films n'ont pas été proposé. Cette semaine c'est le tour de " .$proposeur_cette_semaine."</mark>";
        }else{//Sinon on indique que aucun proposeur n'est défini
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
echo "<br/>";
echo '<h2 class="text-warning">Les prochains proposeurs</h2><br/>';
printNextproposeurs($id_current_semaine);
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