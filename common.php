<?php
$curdate=new DateTime();

$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
$next_friday = $curdate->modify('next friday')->format('Y-m-d');

$requete = $bdd->query("SELECT id FROM semaine WHERE jour ='".$next_friday."'");
$current_semaine = $requete->fetch();

echo 'current_semaine' .$current_semaine['id'];
$id_current_semaine = $current_semaine['id'];

$requete2 = $bdd->query("SELECT COUNT(*) AS nb_votes_current_semaine FROM a_vote WHERE semaine = '".$id_current_semaine."'");
$nb_votes = $requete2->fetch()['nb_votes_current_semaine'];

$requete3 = $bdd->query("SELECT COUNT(*) AS nb_personne FROM membre");
$nb_personnes = $requete3->fetch()['nb_personne'];
echo '<br/>';
print_r($requete3->fetch());
echo 'nb_personne' .$nb_personnes;
$vote_termine_cette_semaine = ($nb_personnes == $nb_votes);


function printFilmsProposes($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $requete7 = $bdd->query("SELECT film AS film_id FROM proposition WHERE semaine = '".$id_semaine."'");
  $un_film_propose = false;
  while ($film = $requete7->fetch()){
    $un_film_propose = true;
    $requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
    echo $requete6->fetch()['titre'].'<br/>';
    }
    if(!$un_film_propose){
      echo 'Aucun film n\'a été proposé';
    }
}



function printResultatVote($id_semaine){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete5= $bdd->query("SELECT film AS id_best_film FROM proposition WHERE semaine = '".$id_semaine."' ORDER BY score DESC LIMIT 1");
    if($data=$requete5->fetch()){
      $id_best_film=$data['id_best_film'];
      $requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$id_best_film);
      echo 'Le film retenu est ' .$requete6->fetch()['titre'];
    }else{
      echo 'Il n\'y a pas encore eu de propositions cette semaine';
    }
   
}



?>