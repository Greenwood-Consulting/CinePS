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

<!--link href="./main.3f6952e4.css" rel="stylesheet">
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
        <!--button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
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
      echo "<a href='propose_film.php'><button type='button' class='btn btn-warning'>Ajouter un nouveau film</button></a>";}
      ?>
  
  
  
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

/*if($vote_period){
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
//echo $connecte;


$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
$next_friday = $curdate->modify('next friday')->format('Y-m-d');
echo 'next_friday'.$next_friday;
$requete = $bdd->query("SELECT id FROM semaine WHERE jour ='".$next_friday."'");
$current_semaine = $requete->fetch();

echo 'current_semaine' .$current_semaine['id'];
$id_current_semaine = $current_semaine['id'];

$requete1 = $bdd->query("SELECT id FROM proposition WHERE id = '".$id_current_semaine."'");
$proposition_semaine =  $requete1->fetch();
if($proposition_semaine){
  echo 'Il y a deja eu des propositions cette semaine';
}else{
  echo "il n'y a pas encore de proposition";
}

$requete2 = $bdd->query("SELECT COUNT(*) AS nb_votes_current_semaine FROM a_vote WHERE semaine = '".$id_current_semaine."'");
$nb_votes = $requete2->fetch()['nb_votes_current_semaine'];
echo '<br/>';
print_r($requete2->fetch());
echo '<br/>';
//echo 'id_current_semaine' .$id_current_semaine;
//echo 'nb_vote' .$nb_votes;

$requete3 = $bdd->query("SELECT COUNT(*) AS nb_personne FROM membre");
$nb_personnes = $requete3->fetch()['nb_personne'];
echo '<br/>';
print_r($requete3->fetch());
//echo 'nb_personne' .$nb_personnes;
$vote_termine_cette_semaine = ($nb_personnes == $nb_votes);

if($vote_termine_cette_semaine){
  echo 'Le vote est terminé';
}else{
  echo 'Le vote n est pas terminé : <a href="vote.php" class="text-warning">Pour les votes</a><br/>';
}

$user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
$id_utlisateur_connecte = $user->fetch()['id'];
$requete4= $bdd->query("SELECT COUNT(votant) AS a_vote_current_user_semaine FROM a_vote WHERE (id = '".$id_utlisateur_connecte. "' AND semaine = '".$id_current_semaine."')");
$current_user_a_vote=$requete4->fetch()['a_vote_current_user_semaine']==1;
if($current_user_a_vote){
  echo "l'utilsateur courrant a voté";
}else{
  echo "l'utilisateur courrant n'a pas encore voté";
}*/
$vote_period = true;
$proposition_semaine = true;
$vote_termine = true;
$user_vote = true;

echo '<br/>';
echo '<br/>';
echo '<br/>';
echo 'Page d\'accueil :';
echo '<br/>';
echo '<br/>';

if(isset($_SESSION['user'])){
  echo "Utilisateur connecté : ".$_SESSION['user'];
  if($vote_period){
    if($proposition_semaine){
      if($vote_termine){
        echo 'Le film retenu est: One piece';
      }else{
        if($user_vote){

        }
      }
          
            
    }else{

    }

  }else{

  }
}else{
  echo 'La proposition n\'a pas encore été faite';
  
}



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