<?php
session_start();
include('env.php');
include('common.php');
include('header.php');

// Mise à jour de la table a_vote pour l'utilisateur connecté
$array_body_avote = array();
$json_body_avote = json_encode($array_body_avote);
echo 'session'. $_SESSION['user'];
$json_avote = callAPI_POST("/api/avote/".$_SESSION['user'], $json_body_avote);
$array_avote = json_decode($json_avote);


if(!isset($_POST['abstention'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
    foreach($_POST as $proposition_id=>$film_vote){// Mise à jour des scroes de tous les films
      // préparation du body de la requête POST
      $array_vote = array(
        'membre' => $_SESSION['user'],
        'proposition' => $proposition_id,
        'vote' => $film_vote
      );
      $json_vote = json_encode($array_vote);

      // call API
      $json_vote = callAPI_POST("/api/saveVoteProposition", $json_vote);
      $array_vote = json_decode($json_vote);
  }
}

header('Location: index.php');
exit();
?>