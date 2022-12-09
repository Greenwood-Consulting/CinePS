<?php
// Get proposition_semaine
$get_proposition_semaine = $bdd->query("SELECT proposition_termine FROM semaine WHERE id = '".$id_current_semaine."'");
$proposition_semaine = 0;
if($result_proposition_semaine =  $get_proposition_semaine->fetch()){//si la proposition de la semaine est égal à 0
  $proposition_semaine = $result_proposition_semaine['proposition_termine'];
}

//Calcule etat is_proposeur
$get_proposeur = $bdd->query("SELECT proposeur FROM semaine WHERE id = '".$id_current_semaine."'");
if($requete_proposeur_cette_semaine = $get_proposeur->fetch()){//Si la personne est proposeur
  $proposeur_cette_semaine = $requete_proposeur_cette_semaine['proposeur'];
}else{//sinon elle ne l'est pas 
  $proposeur_cette_semaine = 0;
}
$is_proposeur = false;
if(isset($_SESSION['user'])){//utilisateur connecté
$is_proposeur = $_SESSION['user'] == $proposeur_cette_semaine;
}



// get état vote_termine_cette_semaine
$get_nb_votes = $bdd->query("SELECT COUNT(*) AS nb_votes_current_semaine FROM a_vote WHERE semaine = '".$id_current_semaine."'");
$nb_votes = $get_nb_votes->fetch()['nb_votes_current_semaine'];
$get_nb_personnes = $bdd->query("SELECT COUNT(*) AS nb_personne FROM membre");
$nb_personnes = $get_nb_personnes->fetch()['nb_personne'];
echo '<br/>';
print_r($get_nb_personnes->fetch());
$vote_termine_cette_semaine = (($nb_personnes - 1) == $nb_votes);

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  $user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
  $id_utlisateur_connecte = $user->fetch()['id'];
  $get_current_user_a_vote = $bdd->query("SELECT COUNT(votant) AS a_vote_current_user_semaine FROM a_vote WHERE (votant = '".$id_utlisateur_connecte. "' AND semaine = '".$id_current_semaine."')");
  $current_user_a_vote=$get_current_user_a_vote->fetch()['a_vote_current_user_semaine']>0;
}

//indique si le thème a été proposé ou non
$get_theme = $bdd->query("SELECT theme FROM semaine WHERE id ='".$id_current_semaine."'");
$theme = $get_theme->fetch()['theme'];
$etat_theme_non_propose = $theme == "";

?>