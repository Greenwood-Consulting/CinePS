<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

// Date du jour
$curdate=new DateTime();

// Get état id_current_semaine
if ($curdate->format('D')=="Fri"){ // Si nous sommes vendredi, alors id_current_semaine est défini par ce vendredi
  $friday_current_semaine = $curdate->format('Y-m-d');
} else { // Sinon id_current_semaine est défini par vendredi prochain
  $friday_current_semaine = $curdate->modify('next friday')->format('Y-m-d');
}
$requete = $bdd->query("SELECT id FROM semaine WHERE jour ='".$friday_current_semaine."'");
if($current_semaine = $requete->fetch()){
  $id_current_semaine = $current_semaine['id'];
}else{
  $id_current_semaine = 0;
}

//Récupération des mails
//$requete_mail = $bdd->query("SELECT mail FROM membre");

//Fonction d'affichage
function printFilmsProposes($id_semaine){
  echo '<h2 class="text-warning">Liste des films proposés</h2><br/>';
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $requete7 = $bdd->query("SELECT film AS film_id FROM proposition WHERE semaine = '".$id_semaine."'");
  $un_film_propose = false;
  while ($film = $requete7->fetch()){
    $un_film_propose = true;
    $ajout_film = $bdd->query('SELECT titre, sortie_film, imdb FROM film WHERE id = '.$film['film_id']);
    $data_film = $ajout_film->fetch();
    echo '<mark>'.$data_film['titre'].' ';
    echo $data_film['sortie_film'];
    echo '<a class="text-dark" href = '.$data_film['imdb'].' '.'> Lien imdb </a><br/></mark>';
    }
    if(!$un_film_propose){//si aucun film n'est proposé
      echo '<mark> Aucun film n\'a été proposé </mark>';
    }
}

function printResultatVote($id_semaine){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $film_gagnant= $bdd->query("SELECT film AS id_best_film FROM proposition WHERE semaine = '".$id_semaine."' ORDER BY score DESC LIMIT 1");
    if($data=$film_gagnant->fetch()){//si le vote est fini on affiche le vainqueur
      $id_best_film=$data['id_best_film'];
      $film_retenu = $bdd->query('SELECT titre FROM film WHERE id = '.$id_best_film);
      echo '<mark>Tous les utilisateurs ont voté. Le film retenu est : <br ><b>' .$film_retenu->fetch()['titre'].'</b></mark>';
    }else{//sinon il n'y a pas de propositions
      echo '<mark>Il n\'y a pas encore eu de propositions cette semaine</mark>';
    }
}
function printUserVote($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $user_vote = $bdd->query("SELECT votant AS votant_id FROM a_vote WHERE semaine = '".$id_semaine."'");
  $une_personne_a_vote = false;
  while($data = $user_vote->fetch()){
    $une_personne_a_vote = true;
    $user_qui_a_vote = $data['votant_id'];
    $user_a_vote = $bdd->query('SELECT Prenom FROM membre WHERE id = '.$user_qui_a_vote);
    echo '<mark>Les personnes qui ont voté sont : <br/></mark>';
    echo '<mark><b>' .$user_a_vote->fetch()['Prenom'].'</b></mark><br/>';
  }
  if(!$une_personne_a_vote){
    echo '<mark>Personne n\'a voté pour l\'instant <br/></mark>';
  }
}

function printAllfilmsSemaines($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $requete8 = $bdd->query("SELECT film AS film_id FROM proposition WHERE semaine = '".$id_semaine."'");
  $un_film_propose = false;
  while ($film = $requete8->fetch()){
    $un_film_propose = true;
    $requete_titre_film = $bdd->query('SELECT titre, imdb FROM film WHERE id = '.$film['film_id']);
    $data_film = $requete_titre_film->fetch();
    echo '<mark>'.$data_film['titre'];
    echo '<a class="text-dark" href = '.$data_film['imdb'].' '.'> Lien imdb </a><br/></mark>';
  }
  if(!$un_film_propose){
    echo "<mark> Pas de film pour cette semaine </mark>";
  }
}
//Affiche la liste de tout les proposeurs suivant la semaine $id_semaine
function printNextproposeurs($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $date = date('Y-m-d');
  $requete_jour_correspondant = $bdd->query("SELECT jour FROM semaine WHERE id = ".$id_semaine);
  $jour_correspondant_id_semaine = $requete_jour_correspondant->fetch()['jour'];
  $next_proposeurs = $bdd->query("SELECT proposeur, jour FROM semaine WHERE jour >= '" .$jour_correspondant_id_semaine."' ORDER BY jour");
    while ($data = $next_proposeurs->fetch()){
    echo $data['proposeur'];
    echo $data['jour'].'</br>';
  }
}



   ?>