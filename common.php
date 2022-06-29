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
  echo '<mark>Les personnes qui ont voté sont : <br/></mark>';
  while($data = $user_vote->fetch()){
    $user_qui_a_vote = $data['votant_id'];
    $user_a_vote = $bdd->query('SELECT Prenom FROM membre WHERE id = '.$user_qui_a_vote);
    echo '<mark><b>' .$user_a_vote->fetch()['Prenom'].'</b></mark><br/>';
  }
  
  }



?>