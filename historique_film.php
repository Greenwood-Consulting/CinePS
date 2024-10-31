<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="description" name="description">
  <meta name="google" content="notranslate" />
  <meta content="Mashup templates have been developped by Orson.io team" name="author">
  <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"-->
  <link href="historique_film.css" rel="stylesheet">

  <!--Disable tap highlight on IE-->
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
<div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content"-->
<?php
include('common.php');

// Barre de navigation
?>

<div class="fixed-header">
  <div class="centered-buttons">
    <?php
    include('nav.php'); 
    ?>
  </div>
  <div class="right-form">
    <?php
    include('auth_form.php');
    ?>
  </div>
</div>

<div class="main-content">

<?php



// Affichage du titre de la page en fonction du filtre utilisateur
if(isset($_POST['member_filter'])){
  $id_membre = $_POST['user'];
  if ($_POST['user'] == 0) {
    echo "<h1 id = 'titre'>Historique des propositions</h1>";
  } else {
    $json_array_id_membre = callAPI("/api/membres/". $id_membre);
    $array_id_membre = json_decode($json_array_id_membre);
    echo "<h1 id = 'titre'>Historique des propositions de ".$array_id_membre->Nom."</h1>";
  }
}else{
  echo "<h1 id = 'titre'>Historique des propositions</h1>";
}





// On récupère les anciennes semaines
$get_historique = callAPI("/api/historique");
$array_historique = json_decode($get_historique);

$array_historique_semaines = $array_historique->semaines;
$array_historique_membres = $array_historique->membres;

// Affichage du dropdown de sélection du membre pour filtrer
$array_proposeurs = array();
foreach($array_historique_semaines as $semaine){

  $array_proposeurs[$semaine->proposeur->Nom] = $semaine->proposeur;
}
$tous = new stdClass();
$tous->Nom = "Tous les utilisateurs";
$tous->id = 0;
$array_proposeurs['tous'] = $tous;
echo'<form method="post" action="historique_film.php" class = "main-zone">
    <label>Membres</label>
        <select class="text-dark" name="user">';
foreach($array_proposeurs as $proposeur){ //Afficher un utlisateur
     echo"<option class='text-dark' value=".$proposeur->id.">". $proposeur->Nom."</option>";
}
echo"</select>";
echo '<button type="submit" name="member_filter">Filtrer</button>';
echo "</form>";

// Traiter le cas où on vient d'appuyer sur le bouton pour désigner le film gagant
if (isset($_POST['designer_film_gagant'])) {
  // préparation du body de la requête PATCH
  $array_semaine = array(
    'proposition_gagnante' => $_POST['filmGagnant']
  );
  $json_semaine = json_encode($array_semaine);

  // call API
  $json_semaine = callAPI_PATCH("/api/semaine/".$_POST['semaineId'], $json_semaine);
  $array_semaine = json_decode($json_semaine);
}

// Filtrer les propositions du membre sélectionné
if (isset($_POST['member_filter']) && $_POST['user'] != 0) {
  $array_historique_semaines = array_filter($array_historique_semaines, function($semaine) use ($id_membre) {
    return ($semaine->proposeur->id == $id_membre) && ($semaine->type == 'PSAvecFilm');
  });
}

// Afficher l'historique de chaque semaine
foreach($array_historique_semaines as $semaine){
  // création d'une DateTime afin de pouvoir formater
  $dateSemaine = DateTime::createFromFormat('Y-m-d\TH:i:sP', $semaine->jour);
  if(!(($semaine->id == $id_current_semaine) && !$vote_termine_cette_semaine)){//Toutes les semaines précédentes ou pour la semaine courrante avec vote terminée
    // TODO gérer le if dans service CinePS-API, pas ici
    
    if ( $semaine->type == 'PSAvecFilm'){ // semaine normale avec film
      // Titre de la semaine
      echo "<h2> Les propositions de ".$semaine->proposeur->Nom;
      echo " Pour la semaine du ".$dateSemaine->format('Y-m-d'). "</h2><br/>";
      
      // Affichage du thème
      echo "<p><b>Thème : ".$semaine->theme."</b></p><br />";

      // Formulaire pour désigner le film gagnant
      if (isset($_SESSION['user']) && $_SESSION['user'] == 1 ){ // Si utilisateur bebert
        echo '<form method="post" action="historique_film.php" class="form-film-gagnant">
                <label>Spécifier le film gagant</label>
                <select class="text-dark" name="filmGagnant">';
                foreach($semaine->propositions as $proposition){ //Afficher le titre du film de la proposition
                  echo"<option class='text-dark' value=".$proposition->id.">". $proposition->film->titre."</option>";
                }
        echo "  </select>";
        echo '<input type="hidden" id="semaineId" name="semaineId" value="'.$semaine->id.'" />';
        echo '  <button type="submit" name="designer_film_gagant">Désigner le film gagant</button>';
        echo "</form><br />";
      }
  
      printChoixvoteFromArray($semaine, $array_historique_membres);
    }

    if ($semaine->type == 'PasDePS'){ // semaine sans PS
      echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d'). "</h2><br/>";
      echo "<p><b>Pas de PS</b></p><br />";
    }

    if ($semaine->type == 'PSSansFilm'){ // Semaine PS sans film
      echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d'). "</h2><br/>";
      echo "<p><b>Pas de film</b></p><br />";
    }

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

  <!-- Script JavaScript intégré -->
  <script>
    // S'exécute après le chargement de la page
    window.addEventListener('load', function() {
      const header = document.querySelector('.fixed-header');
      const mainContent = document.querySelector('.main-content');
      mainContent.style.marginTop = header.offsetHeight + 'px';
    });
  </script>
</body>