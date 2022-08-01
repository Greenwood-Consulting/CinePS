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
<div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content">
              <h1 class="text-warning">Historique des propositions</h1>
<?php 
include('common.php');
$requete8 = $bdd->query("SELECT id, jour, proposeur FROM semaine");
  while ($semaine = $requete8->fetch()){
    $id_semaine = $semaine['id'];
    $jour_semaine = $semaine['jour'];
    $proposeur_semaine = $semaine['proposeur'];
    echo " <h2 class = 'bg-primary text-white'> Les propositions de ".$proposeur_semaine;
    echo " Pour la semaine du ".$jour_semaine. "</h2><br/>";
    printAllfilmsSemaines($id_semaine);
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