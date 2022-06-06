<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

// Dates
$curdate=new DateTime();
$next_friday = $curdate->modify('next friday')->format('Y-m-d');

// Get état id_current_semaine
$requete = $bdd->query("SELECT id FROM semaine WHERE jour ='".$next_friday."'");
$current_semaine = $requete->fetch();
$id_current_semaine = $current_semaine['id'];

// Get proposition_semaine
$requete1 = $bdd->query("SELECT proposition_termine FROM semaine WHERE id = '".$id_current_semaine."'");
$proposition_semaine =  $requete1->fetch()['proposition_termine'];
$requete10 = $bdd->query("SELECT proposeur FROM semaine WHERE id = '".$id_current_semaine."'");
$proposeur_cette_semaine = $requete10->fetch()['proposeur'];
$is_proposeur = false;
if(isset($_SESSION['user'])){
$is_proposeur = $_SESSION['user'] == $proposeur_cette_semaine;
}

// get état vote_termine_cette_semaine
$requete2 = $bdd->query("SELECT COUNT(*) AS nb_votes_current_semaine FROM a_vote WHERE semaine = '".$id_current_semaine."'");
$nb_votes = $requete2->fetch()['nb_votes_current_semaine'];
$requete3 = $bdd->query("SELECT COUNT(*) AS nb_personne FROM membre");
$nb_personnes = $requete3->fetch()['nb_personne'];
echo '<br/>';
print_r($requete3->fetch());
$vote_termine_cette_semaine = ($nb_personnes == $nb_votes);

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur a voté
  $user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
  $id_utlisateur_connecte = $user->fetch()['id'];
  $requete4= $bdd->query("SELECT COUNT(votant) AS a_vote_current_user_semaine FROM a_vote WHERE (votant = '".$id_utlisateur_connecte. "' AND semaine = '".$id_current_semaine."')");
  $current_user_a_vote=$requete4->fetch()['a_vote_current_user_semaine']==1;
}

function printFilmsProposes($id_semaine){
  echo "<h2>Liste des films proposés</h2>";
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $requete7 = $bdd->query("SELECT film AS film_id FROM proposition WHERE semaine = '".$id_semaine."'");
  $un_film_propose = false;
  while ($film = $requete7->fetch()){
    $un_film_propose = true;
    $ajout_film = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
    echo $ajout_film->fetch()['titre'].'<br/>';
    }
    if(!$un_film_propose){//si aucun film n'est proposé
      echo 'Aucun film n\'a été proposé';
    }
}

function printResultatVote($id_semaine){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $film_gagnant= $bdd->query("SELECT film AS id_best_film FROM proposition WHERE semaine = '".$id_semaine."' ORDER BY score DESC LIMIT 1");
    if($data=$film_gagnant->fetch()){//si le vote est fini on affiche le vainqueur
      $id_best_film=$data['id_best_film'];
      $film_retenu = $bdd->query('SELECT titre FROM film WHERE id = '.$id_best_film);
      echo 'Tous les utilisateurs ont voté. Le film retenu est : <br ><b>' .$film_retenu->fetch()['titre'].'</b>';
    }else{//sinon il n'y a pas de propositions
      echo 'Il n\'y a pas encore eu de propositions cette semaine';
    }
}



?>