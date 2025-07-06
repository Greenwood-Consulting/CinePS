<?php

//Calcule etat is_proposeur
$proposeur_cette_semaine = $json_current_semaine->proposeur->id;
$is_proposeur = false;
if(isset($_SESSION['user'])){//utilisateur connecté
  $is_proposeur = $_SESSION['user'] == $proposeur_cette_semaine;
}

// état proposition_semaine 
// TODO : renommer proposition_semaine en is_proposition_terminée
$proposition_semaine = $json_current_semaine->proposition_termine;

// get état vote_termine_cette_semaine
// @TODO : remplacer par currentSemaine ?
$vote_termine_cette_semaine = call_API("/api/isVoteTermine/".$id_current_semaine, "GET");

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  $votants = $json_current_semaine->votants;
  foreach ($votants as $votant) {
    if ($votant->votant->id == $_SESSION['user']) {
      $current_user_a_vote = true;
      break;
    }
  }
}

//indique si le thème a été proposé ou non
$etat_theme_non_propose = $json_current_semaine->theme == "";

$is_actif = true;
// Récupérer les membres depuis l'API
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  $json_membre = array_values(array_filter($membres, fn($m) => $m->id == $_SESSION['user']))[0] ?? null;
  //indique si le membre est actif ou non
  $is_actif = $json_membre->actif;
}
?>