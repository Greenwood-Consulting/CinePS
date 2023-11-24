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



if(isset($_POST['submit_button'])){
  $id_membre = $_POST['user'];
  $json_array_id_membre = callAPI("/api/membres/". $id_membre);
  $array_id_membre = json_decode($json_array_id_membre);
  echo "<h1 id = 'titre'>Historique des propositions de ".$array_id_membre->Nom."</h1>";
}else{
  echo "<h1 id = 'titre'>Historique des propositions</h1>";
}



echo "<a href='index.php'><button type='button' class='btn btn-warning'>Page d'accueil</button></a>";



// On récupère les anciennes semaines
$get_anciennes_semaines = callAPI("/api/anciennesSemaines");
$array_anciennes_semaines = json_decode($get_anciennes_semaines);


$array_proposeurs = array();
foreach($array_anciennes_semaines as $semaine){
  $array_proposeurs[$semaine->proposeur->Nom] = $semaine->proposeur;
}
$tous = new stdClass();
$tous->Nom = "Tous les utilisateurs";
$tous->id = 0;
$array_proposeurs['tous'] = $tous;
echo "<p>";
echo'<form method="post" action="historique_film.php">
    <label>Membres</label>
        <select class="text-dark" name="user">';
foreach($array_proposeurs as $proposeur){ //Afficher un utlisateur
     echo"<option class='text-dark' value=".$proposeur->id.">". $proposeur->Nom."</option>";
}
echo"</select>";
echo '<button type="submit" name="submit_button">Afficher le message</button>';
echo "</form>";
echo "</p>";
if (isset($_POST['submit_button']) && $_POST['user'] != 0) {
   // Afficher les propositions du membre sélectionné
   $selectedUserId = $_POST['user'];
  foreach ($array_anciennes_semaines as $semaine) {
    if (($semaine->proposeur->id == $selectedUserId) && !(($semaine->id == $id_current_semaine) && !$vote_termine_cette_semaine)) {
        $dateSemaine = DateTime::createFromFormat('Y-m-d\TH:i:sP', $semaine->jour);
        echo "<h2> Les propositions de " . $semaine->proposeur->Nom;
        echo " Pour la semaine du " . $dateSemaine->format('Y-m-d') . "</h2><br/>";
        echo "<p><b>Thème : " . $semaine->theme . "</b></p>";
        printChoixvote($semaine->id);
    }
  }
}
else{
  foreach($array_anciennes_semaines as $semaine){
    // création d'une DateTime afin de pouvoir formater
    $dateSemaine = DateTime::createFromFormat('Y-m-d\TH:i:sP', $semaine->jour);
    if(!(($semaine->id == $id_current_semaine) && !$vote_termine_cette_semaine)){//Toutes les semaines précédentes ou pour la semaine courrante avec vote terminée
      // TODO gérer le if dans service CinePS-API, pas ici
      echo "<h2 > Les propositions de ".$semaine->proposeur->Nom;
      echo " Pour la semaine du ".$dateSemaine->format('Y-m-d'). "</h2><br/>";
      echo "<p><b>Thème : ".$semaine->theme."</b></p>";
  
      printChoixvote($semaine->id);
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