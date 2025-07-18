<?php
include('includes/init.php');
include('common.php');
include 'header.php';
?>

  <link href="historique_film.css" rel="stylesheet">

  <title>Historique</title>  



</head>

<!--
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

$voyelles = ['A', 'E', 'I', 'O', 'U', 'Y'];

// Affichage du titre de la page en fonction du filtre utilisateur
if(isset($_POST['member_filter'])){
  $id_membre = $_POST['user_filter'];
  if ($_POST['user_filter'] == 0) {
    echo "<h1 class = 'titre'>Historique des propositions</h1>";
  } else {
    $json_membre = array_values(array_filter($membres, fn($m) => $m->id == $id_membre))[0] ?? null;
    $nom_membre = $json_membre->nom;
    if (in_array(strtoupper($nom_membre[0]), $voyelles)) {
      echo "<h1 class = 'titre'>Historique des propositions d'".$nom_membre."</h1>";
    } else {
      echo "<h1 class = 'titre'>Historique des propositions de ".$nom_membre."</h1>";
    }
  }
}else{
  echo "<h1 class = 'titre'>Historique des propositions</h1>";
}





// On récupère les anciennes semaines
$json_historique = call_API("/api/historique", "GET");

$array_historique_semaines = $json_historique->semaines;
$array_historique_membres = $json_historique->membres;

// Affichage du dropdown de sélection du membre pour filtrer
$array_proposeurs = array();
$tous = new stdClass();
$tous->nom = "Tous les utilisateurs";
$tous->id = 0;
$array_proposeurs['tous'] = $tous;
foreach($array_historique_semaines as $semaine){

  $array_proposeurs[$semaine->proposeur->nom] = $semaine->proposeur;
}
echo'<form method="post" action="historique_film.php" class = "main-zone">
    <label>Membres</label>
        <select class="text-dark" name="user_filter">';
foreach($array_proposeurs as $proposeur){ //Afficher un utlisateur
    echo"<option class='text-dark' value=".$proposeur->id.($proposeur->id == $_POST['user_filter'] ? " selected" : "").">". $proposeur->nom."</option>";
}
echo"</select>";
echo '<button type="submit" name="member_filter">Filtrer</button>';
echo "</form>";

// Traiter le cas où on vient d'appuyer sur le bouton pour éditer la semaine
if (isset($_POST['designer_film_gagant'])) {
  // préparation du body de la requête PATCH
  $array_semaine = array();

  if (isset($_POST['typeSemaine']) && $_POST['typeSemaine'] == 'PSDroitDivin') {
    // préparation du body de la requête POST d'ajout de film
    $titre_film = addslashes($_POST['droit_divin_titre_film']);
    $sortie_film = addslashes($_POST['droit_divin_date_film']); 
    $imdb_film = addslashes($_POST['droit_divin_lien_imdb']);  
    $array_semaine['type_semaine'] = 'PSDroitDivin';
    $array_semaine['droit_divin_titre_film'] = $titre_film;
    $array_semaine['droit_divin_date_film'] = $sortie_film;
    $array_semaine['droit_divin_lien_imdb'] = $imdb_film;
  }

  if ($_POST['proposeurSemaine'] != 'no') {
    $array_semaine['proposeur_id'] = $_POST['proposeurSemaine'];
  }
  if ($_POST['filmGagnant'] != 'no') {
    $array_semaine['proposition_gagnante'] = $_POST['filmGagnant'];
  }
  if (isset($_POST['raison_changement_film']) && $_POST['raison_changement_film'] != '') {
    $array_semaine['raison_changement_film'] = $_POST['raison_changement_film'];
  }
  $json_semaine = json_encode($array_semaine);

  call_API("/api/semaine/".$_POST['semaineId'], "PATCH", $json_semaine);
}

// Filtrer les propositions du membre sélectionné
if (isset($_POST['member_filter']) && $_POST['user_filter'] != 0) {
  $array_historique_semaines = array_filter($array_historique_semaines, function($semaine) use ($id_membre) {
    return ($semaine->proposeur->id == $id_membre) && ($semaine->type == 'PSAvecFilm');
  });
}

function admin_semaine_edit(){
  global $semaine, $array_proposeurs;

  /*********************************************
   * Formulaire admin pour éditer la semaine
   *********************************************/
  // Formulaire pour désigner le film gagnant et le proposeur de la semaine
  if (isset($_SESSION['user']) && $_SESSION['user'] == 1 ){ // Si utilisateur bebert
    echo "<details class = \"texte-historique\"><summary>Editer la semaine</summary>";
    echo '<form method="post" action="historique_film.php">';

    // Dropdown pour choisir le film gagnant
    echo '  <label>Spécifier le film gagnant</label>
            <select class="text-dark" name="filmGagnant">';
            echo"<option class='text-dark' value='no'>-- Spécifier de film gagant --</option>";
            foreach($semaine->propositions as $proposition){ //Afficher le titre du film de la proposition
              echo"<option class='text-dark' value=".$proposition->id.">". $proposition->film->titre."</option>";
            }
    echo "  </select><br />";

    // Dropdown pour choisir le proposeur de la semaine
    echo '<label>Spécifier le proposeur de la semaine</label>
    <select class="text-dark" name="proposeurSemaine">';
    echo"<option class='text-dark' value='no'>-- Changer le proposeur --</option>";
    foreach($array_proposeurs as $proposeur){ //Afficher le titre du film de la proposition
      echo"<option class='text-dark' value=".$proposeur->id.">". $proposeur->nom."</option>";
    }
    echo "  </select><br />";

    // Champ pour spécifier la raison du choix du changement de film
    echo '<label>Spécifier la raison du choix du changement de film</label>';
    echo '<input type="text" name="raison_changement_film" /><br />';

    // Dropdown pour modifier le type de la semaine
    echo '<label>Modifier le type de la semaine</label>
    <select class="text-dark" name="typeSemaine">
      <option class="text-dark" value="no_type">-- Changer le type de la semaine --</option>
      <option class="text-dark" value="PSAvecFilm">PS avec film</option>
      <option class="text-dark" value="PasDePS">Pas de PS</option>
      <option class="text-dark" value="PSSansFilm">PS sans film</option>
      <option class="text-dark" value="PSDroitDivin">PS de droit divin</option>
    </select><br />';

    // Formulaire pour ajouter un film dans la base de données et le mettre automatiquement comme film gagnant
    echo '<label>Ajouter un film (seulement pour les PS de droit divin)</label>
    <input type="text" name="droit_divin_titre_film" placeholder="Titre du film" class="text-dark" />
    <input type="text" name="droit_divin_lien_imdb" placeholder="Lien imdb" class="text-dark" />
    <input type="number" name="droit_divin_date_film"  placeholder="Année" class="text-dark" />
    <br />';

    // Champ caché pour envoyer l'id de la semaine
    echo '<input type="hidden" id="semaineId" name="semaineId" value="'.$semaine->id.'" />';
    // Bouton pour envoyer le formulaire
    echo '  <button type="submit" name="designer_film_gagant">Editer la semaine</button>';
    echo "</form>";
    echo "</details><br />";
  }
  /*********************************************
   * Fin du formulaire admin
   *********************************************/
}

// Afficher l'historique de chaque semaine
foreach($array_historique_semaines as $semaine){
  // création d'une DateTime afin de pouvoir formater
  $dateSemaine = DateTime::createFromFormat('Y-m-d\TH:i:sP', $semaine->jour);
  if(!(($semaine->id == $id_current_semaine) && !$vote_termine_cette_semaine)){//Toutes les semaines précédentes ou pour la semaine courrante avec vote terminée
    // TODO gérer le if dans service CinePS-API, pas ici
    
    if ( $semaine->type == 'PSAvecFilm'){ // semaine normale avec film
      // Titre de la semaine
      $nom_proposeur = $semaine->proposeur->nom;
      if (in_array(strtoupper($nom_proposeur[0]), $voyelles)) {
        echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d')." - Les propositions d'".$nom_proposeur;
      } else {
        echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d')." - Les propositions de ".$nom_proposeur;
      }
      echo "</h2><br/>";
      
      // Affichage du thème
      echo "<p><b>Thème : ".$semaine->theme."</b></p><br />";

      // Affichage de l'interface d'édition de la semaine
      admin_semaine_edit();

      // Raison propoition choisie
      if ($semaine->raison_proposition_choisie != null){
        echo "<p><b>Cette semaine le film retenu l'a été pour la raison suivante : <br />".$semaine->raison_proposition_choisie."</b></p><br />";
      }
  
      printChoixvoteFromArray($semaine, $array_historique_membres);
    }

    if ($semaine->type == 'PasDePS'){ // semaine sans PS
      echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d')." - Pas de PS 😴</h2><br/>";
      // Affichage de l'interface d'édition de la semaine
      admin_semaine_edit();
    }

    if ($semaine->type == 'PSSansFilm'){ // PS de droit divin
      echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d')." - Pas de film 🥂</h2><br/>";
      // Affichage de l'interface d'édition de la semaine
      admin_semaine_edit();
    }

    if ($semaine->type == 'PSDroitDivin'){ // Semaine PS sans film
      echo "<h2>Semaine du ".$dateSemaine->format('Y-m-d')." - PS de droit Divin 👑</h2><br/>";

      // Affichage de l'interface d'édition de la semaine
      admin_semaine_edit();

      echo "<p><b>Film de Droit Divin :</b> <a href='".$semaine->filmVu->imdb."' target='_blank'>".$semaine->filmVu->titre."</a> (".$semaine->filmVu->sortie_film.")</p><br />";
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
  
  <?php include('footer.php'); ?>

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