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
<Add your content of header -->
<header>
  
  <nav class="navbar  navbar-fixed-top navbar-inverse">
    <div class="container">
    <?php
    include('header.php') 
  ?>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav ">
          <li><a href="./index.php" title="">01 : Accueil</a></li>
          <li><a href="./propose_film.php" title="">02 : Proposition de films</a></li>
          <li><a href="./vote.php" title="">03 : Résultat vote</a></li>
          <li><a href="./contact.html" title="">04 : Contact</a></li>
          <li><a href="./components.html" title="">05 : Components</a></li>
        </ul>

   
    </div>
  </nav>

<div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="hero-full-wrapper">
            <div class="text-content"-->
              <h1>Bonjour,<br>
                <span id="typed-strings">
                  <span>Bienvenue sur le site de CinéPS</span>    
                </span>
                <span id="typed"></span>
                
              </h1>
              <?php
  
  
  if(isset($_SESSION['user'])){
      echo "<a href='propose_film.php'><button type='button' class='btn btn-warning'>Ajouter un nouveau film</button>";}
      ?>
  
  
  <a href="current_week.php" class="text-warning">Pour les test</a>
  <?php
  
 $jour_aujourdhui = date("D");
 $heure_aujourdhui = date("H:i:s");
 echo "<br/>";
 echo "$jour_aujourdhui " ;
 echo $heure_aujourdhui. "<br/>";

 /*if($jour_aujourdhui == "Fri"){
   echo 'Nous sommes vendredi';
 }else{
   echo 'Nous ne sommes pas Vendredi';
 }*/
 
$deb= new DateTime ("Fri 12:00");
$fin = new DateTime("Sat 12:00");
$curdate=new DateTime();
//$curdate=new DateTime("2022-05-13 20:00:00");
//$curdate=new DateTime("9999-01-02");
//echo $curdate->format('Y-m-d H:i:s')."<br/>";
$vote_period=$curdate>=$deb && $curdate <= $fin;

if($vote_period){
  echo 'Nous ne sommes pas en période de vote';;
}
else{
   echo "<a href='vote.php' class='text-warning'>Votez pour le film de la semaine !</a>";
}
echo $deb->format('Y-m-d H:i:s')."<br/>";
echo $fin->format('Y-m-d H:i:s')."<br/>";


 
$vote_periode = $jour_aujourdhui == "Fri";
echo "<br/>";
echo $vote_periode;

$connecte = isset($_SESSION['user']);
echo "<br/>"; 
echo $connecte;

  
 ?>
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