<?php
include('header.php');
include('common.php');

// calcul de la date de fin de la période de vote
$fin_periode_vote = new DateTime(FIN_PERIODE_VOTE, new DateTimeZone('Europe/Paris'));
$fin_periode_vote = $fin_periode_vote->format('Y-m-d H:i:s');

// conversion de la date de fin en timestamp JavaScript
$deadline_vote = strtotime($fin_periode_vote);
$deadline_vote = $deadline_vote*1000;
?>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


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
<style>
  /******************************************************** 
   *  Styles pour l'animation IA 
   ********************************************************/
  /* @Todo : déplacer ces styles dans un fichier CSS dédié quand le refactoring du style de la page index.php sera fait */
  /* Overlay pour l'animation */
  #animationOverlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 100, 0.7);
      z-index: 1200;
      pointer-events: none;
      display: none; /* Caché au départ */
  }

  /* Styles pour les symboles */
  .symbol {
      position: absolute;
      font-size: 24px;
      color: white;
      opacity: 0;
      z-index: 2000;
      animation: fadeInOut 1s ease-in-out forwards;
  }

  /* Animation pour faire apparaître et disparaître les symboles */
  @keyframes fadeInOut {
      0% {
          opacity: 0;
      }
      50% {
          opacity: 1;
      }
      100% {
          opacity: 0;
      }
  }


  /***********************************************************
    *  Styles pour la popup ChatGPT
    ***********************************************************/
  /* Style pour l'overlay de fond */
  .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      visibility: hidden; /* Masqué par défaut */
      opacity: 0;
      transition: opacity 0.3s ease;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1100; /* Assurez-vous que l'overlay a un z-index élevé */
  }

  /* Style pour la boîte modale */
  .popup {
      position: relative;
      width: 300px;
      padding: 20px;
      background: #010101;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      text-align: center;
  }

  /* Style du bouton de fermeture */
  .close-btn {
      cursor: pointer;
      position: absolute;
      top: 10px;
      right: 10px;
  }

  /* Afficher l'overlay et la pop-up */
  .overlay.active {
      visibility: visible;
      opacity: 1;
  }

</style>


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
    include('auth_form.php');
    include('nav.php');
  ?>
    </div>
  </nav>

<div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content">
              <!-- Titre de la page -->
              <h1 class="text-warning">
                <img src="assets/logo/logo.png" alt="CinePS" style="height: 1em; vertical-align: top; position: relative; top: -5px;" />
                CinePS
                <sup>
                  <span style="font-size: 50%; vertical-align: top;">
                    <img src="./assets/icones/intelligence-artificielle8.png" alt="AI Icon" style="width: 50px; height: 50px; vertical-align: middle; filter: drop-shadow(0 0 10px white);">
                    AI Enhanced™
                  </span>
                </sup>
              </h1>

<?php

$jour_aujourdhui = date("D");
 
$deb= new DateTime ("Mon 12:00");
$deb = $deb->modify('-1 week');
$fin = new DateTime(FIN_PERIODE_VOTE);
$curdate=new DateTime();
$vote_period=($curdate>=$deb && $curdate <= $fin);


//Proposition comportement 1 : on vient du bouton end_proposition
if(isset($_POST['end_proposition'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
  // préparation du body de la requête PATCH
  $array_semaine = array(
    'proposition_terminee' => 1
  );
  $json_semaine = json_encode($array_semaine);

  // Terminer les propositions
  call_API("/api/semaine/".$id_current_semaine, "PATCH", $json_semaine);

  // Redirection après mise à jour
  header("Location: ".$_SERVER['PHP_SELF']);
  exit();

  echo '<mark>Les propositions ont été faites pour cette semaine</mark>';
}


//Propostion comportement 2 : on vient du bouton new_proposition
if(isset($_POST['new_proposition'])){//si un nouveau film est proposé
  // préparation du body de la requête POST
  $titre_film = addslashes($_POST['titre_film']);
  $sortie_film = addslashes($_POST['date']); 
  $imdb_film = addslashes($_POST['lien_imdb']);  
  $array_proposition = array(
    'titre_film' => $titre_film,
    'sortie_film' => $sortie_film,
    'imdb_film' => $imdb_film
  );
  $json_proposition = json_encode($array_proposition);

  // Créer une nouvelle proposition
  call_API("/api/proposition", "POST", $json_proposition);

  echo '<br/>';
  echo '<br/>';
  echo '<br/>';
}

if(isset($_POST['new_theme'])){//si on valide le theme
  // préparation du body de la requête POST
  $array_semaine = array(
    'theme' => $_POST['theme_film']
  );
  $json_semaine = json_encode($array_semaine);

  // Définir le thème des propositions de la semaine
  call_API("/api/semaine/".$id_current_semaine, "PATCH", $json_semaine);
}

//Propostion comportement 2 : on vient du bouton seconde_chance
if(isset($_POST['seconde_chance'])){//si un nouveau film est proposé
  $id_proposeur = addslashes($_SESSION['user']);

  // @TODO : à revoir, je comprends pas à quoi ça sert
  $array_proposition = call_API("/api/PropositionPerdante/". $id_proposeur , "GET");

  // Redirection après mise à jour
  header("Location: ".$_SERVER['PHP_SELF']);
  exit();
}

//Propostion comportement 3 : on vient du bouton chatGPT
if(isset($_POST['chatGPT'])){
  if (isset($_POST['theme'])) {
    $theme = addslashes($_POST['theme']);
  }

  // préparation du body de la requête POST
  $array_body = array(
    'theme' => $theme
  );
  $json_body = json_encode($array_body);

  // call API pour créer des propositions avec ChatGPT
  call_API("/api/propositionOpenAI", "POST", $json_body);

  // Redirection après mise à jour
  header("Location: ".$_SERVER['PHP_SELF']);
  exit();

}
?>
<div class="container-fluid mt-9">
<?php



include('calcul_etat.php');

if ($array_current_semaine[0]->type == "PSSansFilm") {
  echo "<mark>Il n'y a pas de film cette semaine</mark>";
}
if ($array_current_semaine[0]->type == "PasDePS") {
  echo "<mark>Il n'y a pas de PS cette semaine</mark>";
}
if ($array_current_semaine[0]->type == "PSAvecFilm") {
  // Affichage de la liste des utilisateurs ayant déjà voté
  printUserAyantVote($id_current_semaine);

  if ($array_current_semaine[0]->proposition_termine){
    echo '<span class="text-warning">Il reste <div id="demo"></div> avant la fin du vote</span>';
  } else {
    echo '<mark>Les propositions ont été faites pour cette semaine</mark>';
  }
  echo '<br/>';

  if($connecte){//l'utilisateur est connecté
    if($vote_period){//nous sommes en période de vote
      if($proposition_semaine){//les propositions ont été faite
        if($vote_termine_cette_semaine){//le vote est terminé
          echo "<h2 class='text-warning'>Résultat du vote</h2><br/>";
          printResultatVote($id_current_semaine);
          echo "<a href='resultat_vote.php'><button type='button' class='btn btn-warning'>Résultat vote</button></a>";
          /*printChoixvote($id_current_semaine);*/

        }else{//le vote n'est pas terminé
          if(!$is_actif){//si il y a des membres actifs
            echo "Votre compte a été desactivé";
          }else{
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
                echo '<h2 class="text-warning">Les films proposés par '.$proposeur_cette_semaine.' pour cette semaine sont:</h2>';
                ?>
                <form method="POST" action="save_vote.php">
                <?php
                // récupération de la semaine courrante (contenant les propositions)
                $array_current_semaine = call_API("/api/currentSemaine", "GET"); // @TODO : renommer $array_current_semaine en $json_currentSemaine
                $proposeur_cette_semaine = $array_current_semaine[0]->proposeur;
                $nombre_proposition = count($array_current_semaine[0]->propositions);

                echo "<table>";
                foreach($array_current_semaine[0]->propositions as $proposition){
                  echo '<tr><td><mark><a class="text-dark" href = '.$proposition->film->imdb.'>' .$proposition->film->titre.' </a></td><td><input class="text-dark" type="number" name="'.$proposition->id.'" value="1" min="1" max="'.$nombre_proposition.'">'.'</mark> </td></tr>';                }
                echo "</table>";
                ?>
                <button type="submit" class="btn btn-warning">Voter</button>
                <button type="submit" name="abstention" class="btn btn-warning">S'abstenir</button> </br>
                <?php
              }
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
              if($etat_theme_non_propose){//si pas de thème déjà défini, on affiche le formulaire
                echo '<input type="text" name="theme_film" placeholder="Thème film" class="text-dark"/>
                      <button type="submit" name="new_theme" class="btn btn-warning">Choisissez un thème</button><br/><br/>';
              }
              ?>
              
              <input type="text" name="titre_film"  placeholder="Titre du film" class="text-dark" />
              <input type="text" name="lien_imdb" placeholder="Lien imdb" class="text-dark"/>
              <input type="number" name="date"  placeholder="Année" class="text-dark" >
              
              <?php
              echo '<button type="submit" name="new_proposition" class="btn btn-warning">Proposer</button><br/>';
              echo '<button type="submit" name="end_proposition"  class="btn btn-warning">Valider les Propositions</button><br/><br/>';
              echo '<button type="submit" name="seconde_chance" class="btn btn-warning">Seconde Chance</button>';
              echo '&nbsp;&nbsp;';
              //echo '<button onclick="openPopup()" class="btn btn-warning">ChatGPT</button>';
              echo '<div id="animationOverlay"></div>';
              ?>
            </form>
            <br />
            <button onclick="openPopup()" class="btn btn-warning">ChatGPT</button>

            <!-- Overlay et contenu du pop-up -->
            <div class="overlay" id="popup-overlay">
              <div class="popup" onclick="event.stopPropagation();">
                <!-- Bouton de fermeture en tant que span -->
                <button class="btn btn-warning close-btn" onclick="closePopup()">&times;</button>
                <h2 class="text-warning">Proposition ChatGPT</h2>

                <form method="POST" action="index.php">
                  <label for="theme">Saisissez un thème et ChatGPT choisira 5 films sur ce thème :</label>
                  <input type="text" id="theme" name="theme" value="<?php  echo $array_current_semaine[0]->theme; ?>" class="text-dark">

                  <?php 
                    if (empty($array_current_semaine[0]->theme)) {
                      echo "<br />Pour l'instant aucun thème n'est défini. Dans ce cas ChatGPT choisira des films au hasard. Il y a de bonnes chances qu'on regarde Mulloland Drive cette fois-ci !<br />";
                    } else {
                      echo "<br />Tu as déjà défini un thème mais tu peux encore le changer<br />";
                    }
                  ?>

                  <button type="submit" name="chatGPT" onclick="startAnimation()" class="btn btn-warning">Générer des propositions</button>
                </form>
              </div>
            </div>

            <?php
        }else{//sinon les autres users sont informés que le proposeur n'a pas terminé ses propositions
          if($proposeur_cette_semaine){//Si il y a un proposeur défini on affiche qui c'est
            echo"<mark>Les films n'ont pas été proposé. Cette semaine c'est le tour de " .$array_current_semaine[0]->proposeur->Nom."</mark>";
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



</body>
<script src="assets/js/animation-ia.js"></script>
<script src="assets/js/popup.js"></script>

</html>