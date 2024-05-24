<?php



//Calcule etat is_proposeur
$proposeur_cette_semaine = $array_current_semaine[0]->proposeur->id;
$is_proposeur = false;
if(isset($_SESSION['user'])){//utilisateur connecté
  $is_proposeur = $_SESSION['user'] == $proposeur_cette_semaine;
}

// état proposition_semaine 
// TODO : renommer proposition_seme;aine en is_proposition_terminée
$proposition_semaine = $array_current_semaine[0]->proposition_termine;

// get état vote_termine_cette_semaine
$is_vote_termine = callAPI("/api/isVoteTermine/".$id_current_semaine);
$vote_termine_cette_semaine = json_decode($is_vote_termine);

// get état connecte
$connecte = isset($_SESSION['user']);

// get état current_user_a_vote
$current_user_a_vote = false;
if(isset($_SESSION['user'])){//si l'utilisateur est connecté
  //TODO: Que se passe-t-il si une injection se glisse à la place de la session user
  $user = callAPI("/api/aVoteCurrentSemaine/".$_SESSION['user']);
  $current_user_a_vote = json_decode($user);
}

//indique si le thème a été proposé ou non
$theme = $array_current_semaine[0]->theme;
$etat_theme_non_propose = $theme == "";

print_r($theme);

?>