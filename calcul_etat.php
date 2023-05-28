<?php

// état proposition_semaine 
// TODO : renommer proposition_semaine en is_proposition_terminée
$is_proposition_terminee = callAPI("/isPropositionTerminee/".$id_current_semaine);
$proposition_semaine = json_decode($is_proposition_terminee)[0]->proposition_termine;

//Calcule etat is_proposeur
$current_semaine = callAPI("/api/currentSemaine");
$array_current_semaine = json_decode($current_semaine);
$proposeur_cette_semaine = $array_current_semaine[0]->proposeur;
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
  $user = callAPI("/aVoteCurrentSemaine/".$_SESSION['user']);
  $current_user_a_vote = json_decode($user);
}

//indique si le thème a été proposé ou non
$json_semaine = callAPI("/api/semaine/".$id_current_semaine);
$array_semaine = json_decode($json_semaine);
$theme = $array_semaine->theme;
$etat_theme_non_propose = $theme == "";

?>