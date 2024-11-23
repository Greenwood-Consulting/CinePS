<?php


//Calcule etat is_proposeur
$proposeur_cette_semaine = $array_current_semaine[0]->proposeur->id;
$is_proposeur = false;
if(isset($_SESSION['user'])){//utilisateur connecté
  $is_proposeur = $_SESSION['user'] == $proposeur_cette_semaine;
}

// état proposition_semaine 
// TODO : renommer proposition_semaine en is_proposition_terminée
$proposition_semaine = $array_current_semaine[0]->proposition_termine;

// get état vote_termine_cette_semaine
$is_vote_termine = call_API_GET("/api/isVoteTermine/".$id_current_semaine);
$vote_termine_cette_semaine = json_decode($is_vote_termine);

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  $votants = $array_current_semaine[0]->votants;
  foreach ($votants as $votant) {
    if ($votant->votant->id == $_SESSION['user']) {
      $current_user_a_vote = true;
      break;
    }
  }
}

//indique si le thème a été proposé ou non
$etat_theme_non_propose = $array_current_semaine[0]->theme == "";

$is_actif = true;
// Récupérer les membres depuis l'API
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  $get_membre = call_API_GET("/api/membres/" . $_SESSION['user']);
  $array_membres = json_decode($get_membre);
  //indique si le membre est actif ou non
  $is_actif = $array_membres->actif;
}
?>