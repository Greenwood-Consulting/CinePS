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
  
  <!--link rel="apple-touch-icon" sizes="180x180" href="./assets/apple-icon-180x180.png">
  link href="./assets/favicon.ico" rel="icon"-->

  <title>Title page</title>  

<!--link href="./main.3f6952e4.css" rel="stylesheet"-->
</head>
<?php
session_start();
?>              
<!--body class="minimal">
<div id="site-border-left"></div>
<div id="site-border-right"></div>
<div id="site-border-top"></div>
<div id="site-border-bottom"></div>
<! Add your content of header -->
<header>
  
  <nav class="navbar  navbar-fixed-top navbar-inverse">
    <div class="container">
    <?php

    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete = $bdd->query('SELECT * FROM Membre');
    
    ?>
    <form method="post" action="propose_film.php">
      <label>Membres</label>
    <select name="user">
    <?php
    while($data = $requete->fetch()){
     echo"<option value=".$data['Prenom'].">". $data['Nom']." ".$data['Prenom']."</option>";
    }
    ?>
    </select>
    <button type="submit">Se connecter</button>
  </form>
        <!--button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button-->

   
    </div>
  </nav>
</header>
<!-- Add your site or app content here -->
  <!--div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content">
              <h1 style="color:#FFF000">Bonjour,<br>
                <span id="typed-strings">
                  <span>Bienvenue sur CinéPS</span>
                </span>
                <span id="typed"></span-->
                
                
                
                
                 
    <?php
    //Liste des films
    //echo '<h1 style="color:#FFF000"> Liste de films</h1>';
    ?>
    <form>
      
      <?php
    echo '<h1 > Liste de films</h1>';
    $bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
    $requete = $bdd->query('SELECT * FROM film');
        
    while($data = $requete->fetch()){
        $date = new DateTime($data['date']);
        echo $data['titre']." ".$date->format('d F Y').'<input=>'."<br/>";
    }
    // Liste des membres
  
    //echo '<h1 style="color:#FFF000"> Membre </h1>';
    $requete->closeCursor();
    
              //</h1>
            //</div>
          //</div>
        //</div>
      //</div>
    //</div>
  //</div>7
  ?>
  </input>
  </form>
  <a href="save_film.php"><button>PROPOSITION</button>
  
<!--script>
  document.addEventListener("DOMContentLoaded", function (event) {
     type();
     movingBackgroundImage();
  });
</script>


<script type="text/javascript" src="./main.70a66962.js"></script-->
</body>

</html>