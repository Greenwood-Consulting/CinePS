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
              <h1 id = 'titre'>Historique des propositions</h1>
<?php

echo "<a href='index.php'><button type='button' class='btn btn-warning'>Page d'accueil</button></a>";
include('common.php');
include('calcul_etat.php');
  $requete8 = $bdd->query("SELECT id, jour, proposeur FROM semaine");
  while ($semaine = $requete8->fetch()){
    $id_semaine = $semaine['id'];
    $jour_semaine = $semaine['jour'];
    $proposeur_semaine = $semaine['proposeur'];
    echo " <h2 > Les propositions de ".$proposeur_semaine;
    echo " Pour la semaine du ".$jour_semaine. "</h2><br/>";
    if(($id_semaine == $id_current_semaine) && !$vote_termine_cette_semaine){//Si c'est la semaine en cours et que le vote n'est pas terminé
      echo "Le vote n'est pas terminé, vous ne pouvez pas voir le résultat";  
    }else{
      printVotesSemaine($id_semaine);
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