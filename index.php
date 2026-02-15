<?php
require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/common.php');
require_once(__DIR__ . '/includes/calcul_etat.php');

// ------------- reactions au formulaires ----------------------------
// les en-têtes HTTP (ceci comprend les redirections) doivent être envoyés avant tout contenu HTML, c’est-à-dire avant le premier echo ou tout autre sortie.

// Mise a jour du lien de téléchargement du film
if(isset($_POST['update_dlink'])){//si un nouveau film est proposé
  $value = $_POST['update_dlink'];
  $body = json_encode(['value' => $value]);
  call_API("/api/dlink", "PUT", $body);

   header('Location: ' . base_url('index.php'));
  exit;
}

// Suppression d'une proposition
if(isset($_POST['delete_proposition'])){//si un nouveau film est proposé
  $proposition_id = $_POST['delete_proposition'];

  // Supprimer une proposition
  call_API("/api/proposition/".$proposition_id, "DELETE");

  // Redirection après mise à jour
   header('Location: ' . base_url('index.php'));
  exit;
}

// Proposition comportement 2 : on vient du bouton new_proposition
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

  // Redirection après mise à jour
   header('Location: ' . base_url('index.php'));
  exit;
}

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
   header('Location: ' . base_url('index.php'));
  exit;
}

//si on valide le theme
if(isset($_POST['update_theme'])){
  // préparation du body de la requête POST
  $array_semaine = array(
    'theme' => $_POST['theme_film']
  );
  $json_semaine = json_encode($array_semaine);

  // Définir le thème des propositions de la semaine
  call_API("/api/semaine/".$id_current_semaine, "PATCH", $json_semaine);

  // Redirection après mise à jour
   header('Location: ' . base_url('index.php'));
  exit;
}

//Propostion comportement 2 : on vient du bouton seconde_chance
if(isset($_POST['seconde_chance'])){//si un nouveau film est proposé
  $id_proposeur = addslashes($_SESSION['user']);

  $array_proposition = call_API("/api/secondeChance/".$id_proposeur , "POST");

  // Redirection après mise à jour
   header('Location: ' . base_url('index.php'));
  exit;
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
   header('Location: ' . base_url('index.php'));
  exit;
}

// ------------- fin des reactions au formulaires ----------------------------

require_once(__DIR__ . '/includes/header.php'); ?>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="<?= base_url('nav_temp.css') ?>" rel="stylesheet">


<?php 
// Gestion du compte à rebours de la période de vote
$displayCountdown = $proposition_semaine && !$vote_termine_cette_semaine;

if($displayCountdown): ?>
  <script>
    // Injection de la deadline (variable PHP) dans une variable Javascript
    const deadline = new Date(<?= json_encode($vote_deadline) ?>);

    // if deadline is a valid date
    if (!Number.isNaN(deadline.getTime())) {

      // chaque seconde execute le code suivant
      const intervalTimerId = setInterval(function() {
        const now = new Date();
        const remaining = Math.floor((deadline - now) / 1000); // in seconds

        // si la deadline est dépassée
        if (remaining < 0) {
          // stoppe l'execution chaque seconde
          clearInterval(intervalTimerId);
          // Rafraichissement de la page
          window.location.replace(window.location.href);
        } 
        // si la deadline n'est pas dépassée
        else {
          const days = Math.floor(remaining / (60 * 60 * 24));
          const hours = Math.floor((remaining % (60 * 60 * 24)) / (60 * 60));
          const minutes = Math.floor((remaining % (60 * 60)) / 60);
          const seconds = remaining % 60;
          document.getElementById("countdown").textContent = `${days} d ${hours} h ${minutes} m ${seconds} s`;
        }
      }, 1000); // execution à chaque seconde
    }
  </script>
<?php endif; ?>

  <title>CinePS</title>
  
<link href="<?= base_url('main.3f6952e4.css') ?>" rel="stylesheet">
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
    require_once(__DIR__ . '/includes/auth_form.php');
    require_once(__DIR__ . '/includes/nav.php');
  ?>
    </div>
  </nav>

<div class="hero-full-container background-image-container white-text-container" style="background-image: url('<?= base_url('assets/images/space.jpg') ?>')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content">
              <!-- Titre de la page -->
              <h1 class="text-warning">
                <img src="<?= base_url('assets/logo/logo.png') ?>" alt="CinePS" style="height: 1em; vertical-align: top; position: relative; top: -5px;" />
                CinePS
                <sup>
                  <span style="font-size: 50%; vertical-align: top;">
                    <img src="<?= base_url('assets/icones/intelligence-artificielle8.png') ?>" alt="AI Icon" style="width: 50px; height: 50px; vertical-align: middle; filter: drop-shadow(0 0 10px white);">
                    AI Enhanced™
                  </span>
                </sup>
              </h1>
<div class="container-fluid mt-9">
<?php

if ($json_current_semaine->type == "PSSansFilm") {
  echo "<mark>Il n'y a pas de film cette semaine</mark>";
}
if ($json_current_semaine->type == "PasDePS") {
  echo "<mark>Il n'y a pas de PS cette semaine</mark>";
}
if ($json_current_semaine->type == "PSAvecFilm") {
  // Affichage de la liste des utilisateurs ayant déjà voté
  printUserAyantVote($id_current_semaine);

  if ($displayCountdown){
    echo '<span class="text-warning">Il reste <div id="countdown"></div> avant la fin du vote</span>';
  }
  echo '<br/>';

  if($connecte){//l'utilisateur est connecté
    if($proposition_semaine){//les propositions ont été faite
      if($vote_termine_cette_semaine){
        //le vote est terminé
        // L'utilisateur est connecté
        // nous sommes en période de vote
        // les propositions ont été faites
        // le vote est terminé
        echo "<h2 class='text-warning'>Résultat du vote</h2><br/>";
        printResultatVote($id_current_semaine);
        ?>
        <a href="<?= base_url('resultat_vote.php') ?>"><button type="button" class="btn btn-warning">Résultat vote</button></a>

        <!-- TODO this styling should be moved into a dedicated css file -->
        <style>
          .dlink {
            margin-top: 2rem;
          }

          .dlink__a{
            text-decoration: underline black;
          }

          #dlink__update-form {
            display: none;
          }

          .dlink__update-form--input {
            width: 70ch;
          }
        </style>
        <script>
          function toggleUpdateDlinkButton() {
            const el = document.getElementById('dlink__update-form');
            el.style.display = (el.style.display === 'none' || el.style.display === '') 
              ? 'block' 
              : 'none';
          }
        </script>
        <?php if (isset($dLink)): ?>
          <div class="dlink">
            <div>
              <?php if ($dLink !== ''): ?>
                <a href="<?= htmlspecialchars($dLink) ?>" class="dlink__a"><mark>📥 Lien de telechargement</mark></a>
              <?php else: ?>
                <mark>Pas de lien de telechargement disponible</mark>
              <?php endif; ?>
              <button onclick="toggleUpdateDlinkButton()"> ✏️</button>
            </div>
            <div id="dlink__update-form">
              <form method="POST" action="<?= base_url('index.php') ?>">
                  <input type="text" name="update_dlink" class="dlink__update-form--input text-dark" placeholder="https://" value="<?= htmlspecialchars($dLink) ?>" />
                  <button type="submit"> 💾</button>
              </form>
            </div>
          </div>
        <?php endif; 
      }else{
        if(!$is_actif){
          // L'utilisateur est connecté
          // nous sommes en période de vote
          // les propositions ont été faites     
          // le vote n'est pas terminé
          // l'utilisateur connecté est désactivé
          echo "<mark>Votre compte a été desactivé donc vous ne pouvez pas voter</mark><br />";
          printFilmsProposes();
        }else{
          if($is_proposeur){
            // L'utilisateur est connecté
            // nous sommes en période de vote
            // les propositions ont été faites     
            // le vote n'est pas terminé
            // l'utilisateur connecté est actif
            // l'utilisateur connecté est le proposeur de la semaine
            echo '<mark>Vous êtes le proposeur de la semaine donc vous ne pouvez pas voter. Le vote n\'est pas encore terminé.</mark><br />';
            printFilmsProposes();
          }else{
            if($current_user_a_vote){
              // L'utilisateur est connecté
              // nous sommes en période de vote
              // les propositions ont été faites     
              // le vote n'est pas terminé
              // l'utilisateur connecté est actif
              // l'utilisateur connecté n'est pas le proposeur de la semaine
              // l'utilisateur connecté a voté
              echo '<mark>Vous avez déjà voté</mark><br />';
              printFilmsProposes();
            }else{
              // L'utilisateur est connecté
              // nous sommes en période de vote
              // les propositions ont été faites     
              // le vote n'est pas terminé
              // l'utilisateur connecté est actif
              // l'utilisateur connecté n'est pas le proposeur de la semaine
              // l'utilisateur connecté n'a pas encore voté
              $proposeur_cette_semaine = $json_current_semaine->proposeur->nom;

              echo'<h2 class="text-warning">Vous devez voter </h2>';
              echo "<br />";         
              echo '<p class = "text-warning"><b>*Le vote se fait sous forme de classement, par exemple le film que vous préférez voir devra avoir "1" comme vote</b></p>';
              echo '<h2 class="text-warning">Les films proposés par '.$proposeur_cette_semaine.' pour cette semaine sont :</h2>';
              
              $nombre_proposition = count($json_current_semaine->propositions);
              ?>

              <form method="POST" action="<?= base_url('save_vote.php') ?>">
              <table>
                <?php foreach($json_current_semaine->propositions as $proposition): ?>
                  <tr>
                    <td>
                      <mark>
                        <a class="text-dark" href="<?= htmlspecialchars($proposition->film->imdb) ?>" >
                          <?= htmlspecialchars($proposition->film->titre) ?>
                        </a>
                      </mark>
                    </td>
                    <td>
                      <mark>
                        <input class="text-dark" type="number" name="<?= htmlspecialchars($proposition->id) ?>" value="1" min="1" max="<?= htmlspecialchars($nombre_proposition) ?>">
                      </mark>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </table>
              <button type="submit" class="btn btn-warning">Voter</button>
              <button type="submit" name="abstention" class="btn btn-warning">S'abstenir</button> </br>
              <?php
            }
          }
        }
      }
    }else{//la proposition n'est pas encore faite
      if($is_proposeur){
        // L'utilisateur est connecté
        // nous sommes en période de vote
        // la proposition n'est pas encore faite
        // l'utilisateur connecté est le proposeur de la semaine

        //on affiche la liste des films pour le proposeurs tant qu'il n'a pas terminé la proposition
        echo '<mark>Les propositions ne sont pas terminées </mark> <br/><br/>';
        printFilmsProposes();
        echo '<br/><br />';
        ?>

        <form method="POST" action="<?= base_url('index.php') ?>">

          <label>Thème:</label>
          <input type="text" name="theme_film" placeholder="Thème des films" class="text-dark" value="<?= $json_current_semaine->theme ?>" />
          <button type="submit" name="update_theme" class="btn btn-warning"><?= $etat_theme_non_propose? "Choisissez un thème" : "Modifiez le thème" ?></button><br/><br/>
          
          <label>Proposition:</label>
          <!-- Proposition classique -->
          <input type="text" name="titre_film"  placeholder="Titre du film" class="text-dark" />
          <input type="text" name="lien_imdb" placeholder="Lien imdb" class="text-dark"/>
          <input type="number" name="date"  placeholder="Année" class="text-dark" >
          <button type="submit" name="new_proposition" class="btn btn-warning">Proposer un film</button><br/>
          <button type="submit" name="end_proposition"  class="btn btn-warning">Valider les Propositions</button><br/><br/>

          <?php if ($no_propositions): ?>
            <!-- Proposition seconde chance -->
            <button type="submit" name="seconde_chance" class="btn btn-warning">Seconde Chance</button><br /><br />

            <!-- Proposition ChatGPT -->
            <button type="button" onclick="openPopup()" class="btn btn-warning">ChatGPT</button>

            <!-- Overlay et contenu du pop-up pour Proposition ChatGPT-->
            <div class="overlay" id="popup-overlay">
              <div class="popup" onclick="event.stopPropagation();">
                <!-- Bouton de fermeture en tant que span -->
                <button class="btn btn-warning close-btn" onclick="closePopup()">&times;</button>
                <h2 class="text-warning">Proposition ChatGPT</h2>

                <label for="theme">Saisissez un thème et ChatGPT choisira 5 films sur ce thème :</label>
                <input type="text" id="theme" name="theme" value="<?= $json_current_semaine->theme; ?>" class="text-dark">

                <?php if (empty($json_current_semaine->theme)): ?>
                    <br />Pour l'instant aucun thème n'est défini. Dans ce cas ChatGPT choisira des films au hasard. Il y a de bonnes chances qu'on regarde Mulloland Drive cette fois-ci !<br />
                <?php else: ?>
                    <br />Tu as déjà défini un thème mais tu peux encore le changer<br />
                <?php endif; ?>

                <button type="submit" name="chatGPT" onclick="startAnimation()" class="btn btn-warning">Générer des propositions</button>
              </div>
            </div>
            <!-- Overlay etoilé affiché lors de l'appel a chatGPT -->
            <div id="animationOverlay"></div>
          <?php endif; ?>

        </form>

        <?php
      }else{//sinon les autres users sont informés que le proposeur n'a pas terminé ses propositions
        if($proposeur_cette_semaine){
          // L'utilisateur est connecté
          // nous sommes en période de vote
          // la proposition n'est pas encore faite
          // l'utilisateur connecté n'est pas le proposeur de la semaine
          // Il y a un proposeur défini pour cette semaine
          echo "<mark>Les films n'ont pas encore été proposés. Cette semaine c'est le tour de " . $json_current_semaine->proposeur->nom . '</mark>';
        }else{
          // L'utilisateur est connecté
          // nous sommes en période de vote
          // la proposition n'est pas encore faite
          // l'utilisateur connecté n'est pas le proposeur de la semaine
          // Il n'y a pas de proposeur défini pour cette semaine
          echo "<mark>Aucun proposeur n'a encore été défini pour cette semaine.</mark>";
        }
      }
    }
  }else{// l'utilisateur n'est pas connecté
    if($proposition_semaine){//les propositions ont été faites
      if($vote_termine_cette_semaine){
        // L'utilisateur n'est pas connecté
        // nous sommes en période de vote
        // les propositions ont été faites
        // le vote est terminé
        printResultatVote($id_current_semaine);
      }else{
        // L'utilisateur n'est pas connecté
        // nous sommes en période de vote
        // les propositions ont été faites
        // le vote n'est pas terminé
        printFilmsProposes();
      }
    }else{
      // l'utilisateur n'est pas connecté
      // nous sommes en période de vote
      // la proposition n'est pas encore faite
      echo "<mark>Les films n'ont pas encore été proposés. Cette semaine c'est le tour de " . $json_current_semaine->proposeur->nom . '</mark>';
    }
  }
}

// Affichage des proposeurs des prochaines semaines
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
<script type="text/javascript" src="<?= base_url('main.70a66962.js') ?>"></script>



</body>
<script src="<?= base_url('assets/js/animation-ia.js') ?>"></script>
<script src="<?= base_url('assets/js/popup.js') ?>"></script>

</html>