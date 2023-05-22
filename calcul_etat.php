<?php

// état proposition_semaine 
// TODO : renommer proposition_semaine en is_proposition_terminée
$is_proposition_terminee = callAPI("/isPropositionTerminee/".$id_current_semaine);
$proposition_semaine = json_decode($is_proposition_terminee)[0]->proposition_termine;

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
$is_vote_termine = callAPI("/isVoteTermine/".$id_current_semaine);
$vote_termine_cette_semaine = json_decode($is_vote_termine);

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
$json_semaine = callAPI("/api/semaine/".$id_current_semaine);
$array_semaine = json_decode($json_semaine);
$theme = $array_semaine->theme;
$etat_theme_non_propose = $theme == "";

?>