<?php
// Get proposition_semaine
$get_proposition_termine_semaine = $bdd->prepare("SELECT proposition_termine FROM semaine WHERE id = ?");
$get_proposition_termine_semaine->execute([$id_current_semaine]);
$proposition_semaine = 0;
if($result_proposition_semaine =  $get_proposition_termine_semaine->fetch()){//la proposition a été faite
  $proposition_semaine = $result_proposition_semaine['proposition_termine'];
}

//Calcule etat is_proposeur
$get_proposeur = $bdd->prepare("SELECT proposeur FROM semaine WHERE id = ?");
$get_proposeur->execute([$id_current_semaine]);
if($requete_proposeur_cette_semaine = $get_proposeur->fetch()){//Il y a un proposeur 
  $proposeur_cette_semaine = $requete_proposeur_cette_semaine['proposeur'];
}else{//Il n'y a pas de proposeur  
  $proposeur_cette_semaine = 0;
}
$is_proposeur = false;
if(isset($_SESSION['user'])){//utilisateur connecté
$is_proposeur = $_SESSION['user'] == $proposeur_cette_semaine;
}



// get état vote_termine_cette_semaine
$get_nb_votes = $bdd->prepare("SELECT COUNT(*) AS nb_votes_current_semaine FROM a_vote WHERE semaine = ?");
$get_nb_votes->execute([$id_current_semaine]);
$nb_votes = $get_nb_votes->fetch()['nb_votes_current_semaine'];
$get_nb_personnes = $bdd->query("SELECT COUNT(*) AS nb_personne FROM membre");
$nb_personnes = $get_nb_personnes->fetch()['nb_personne'];

$vote_termine_cette_semaine = (($nb_personnes - 1) == $nb_votes);

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  //TODO: Que se passe-t-il si une injection se glisse à la place de la session user
  $user = $bdd->prepare("SELECT id FROM membre WHERE Prenom = ?");
  $user->execute([$_SESSION['user']]);
  $id_utlisateur_connecte = $user->fetch()['id'];
  $get_current_user_a_vote = $bdd->prepare("SELECT COUNT(votant) AS a_vote_current_user_semaine FROM a_vote WHERE (votant = ? AND semaine = ?)");
  $get_current_user_a_vote->execute([$id_utlisateur_connecte, $id_current_semaine]);

  $current_user_a_vote=$get_current_user_a_vote->fetch()['a_vote_current_user_semaine']>0;
}

//indique si le thème a été proposé ou non
$get_theme = $bdd->prepare("SELECT theme FROM semaine WHERE id = ? ");
$get_theme->execute([$id_current_semaine]);
$theme = $get_theme->fetch()['theme'];
$etat_theme_non_propose = $theme == "";

?>