<?php
require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/common.php');

// Mise à jour de la table a_vote pour l'utilisateur connecté
$array_body_avote = array();
$json_body_avote = json_encode($array_body_avote);

call_API("/api/avote/".$_SESSION['user'], "POST", $json_body_avote);

if(!isset($_POST['abstention'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
    foreach($_POST as $proposition_id=>$film_vote){// Mise à jour des scroes de tous les films
      // préparation du body de la requête POST
      $array_vote = array(
        'membre' => $_SESSION['user'],
        'proposition' => $proposition_id,
        'vote' => $film_vote
      );
      $json_vote = json_encode($array_vote);

      call_API("/api/saveVoteProposition", "POST", $json_vote);
  }
}

header('Location: /index.php');
exit();
?>