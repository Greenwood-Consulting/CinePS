<?php
// Get proposition_semaine
$requete1 = $bdd->query("SELECT proposition_termine FROM semaine WHERE id = '".$id_current_semaine."'");
$proposition_semaine = 0;
if($result_proposition_semaine =  $requete1->fetch()){
  $proposition_semaine = $result_proposition_semaine['proposition_termine'];
}

//Calcule etat is_proposeur
$requete10 = $bdd->query("SELECT proposeur FROM semaine WHERE id = '".$id_current_semaine."'");
if($requete_proposeur_cette_semaine = $requete10->fetch()){
  $proposeur_cette_semaine = $requete_proposeur_cette_semaine['proposeur'];
}else{
  $proposeur_cette_semaine = 0;
}
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
$vote_termine_cette_semaine = (($nb_personnes - 1) == $nb_votes);

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  $user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
  $id_utlisateur_connecte = $user->fetch()['id'];
  $requete4= $bdd->query("SELECT COUNT(votant) AS a_vote_current_user_semaine FROM a_vote WHERE (votant = '".$id_utlisateur_connecte. "' AND semaine = '".$id_current_semaine."')");
  $current_user_a_vote=$requete4->fetch()['a_vote_current_user_semaine']>0;
}

?>