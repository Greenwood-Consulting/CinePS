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
?>