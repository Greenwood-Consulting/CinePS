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

  <title>CinePS</title>  

<!--link href="./main.3f6952e4.css" rel="stylesheet"-->
</head>             
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
    include('header.php') 
  ?>
        <!--button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button-->

   
    </div>
  </nav>
</header>

  
  <?php
  if(isset($_SESSION['user'])){
      echo '<a href="propose_film.php"><button>Ajouter un nouveau film</button>';}
      ?>
  <a href="vote.php">Votez pour le film de la semaine !</a>
<!--script>
  document.addEventListener("DOMContentLoaded", function (event) {
     type();
     movingBackgroundImage();
  });
</script>


<script type="text/javascript" src="./main.70a66962.js"></script-->
</body>

</html>