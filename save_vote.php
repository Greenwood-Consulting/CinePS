<?php
include('header.php');
include('common.php');

// Mise à jour de la table a_vote pour l'utilisateur connecté
$user= $bdd->prepare("SELECT id FROM membre WHERE Prenom = ?");
$user->execute([$_SESSION['user']]);
$id_utilisateur_connecte = $user->fetch()['id'];
$insert_a_vote = $bdd->prepare("INSERT INTO `a_vote` (`votant`, `semaine`) VALUES (?,?)");
$insert_a_vote->execute([$id_utilisateur_connecte, $id_current_semaine]);


if(!isset($_POST['abstention'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
    foreach($_POST as $proposition_id=>$film_vote){// Mise à jour des scroes de tous les films
        $get_proposition = $bdd->prepare("SELECT * FROM proposition WHERE id = ?");
        $get_proposition->execute([$proposition_id]);
        $current_proposition = $get_proposition->fetch();
        $new_score= $current_proposition['score'] - $film_vote;
        $update_proposition = $bdd->prepare('UPDATE proposition SET score='.$new_score.' WHERE id= ?');
        $update_proposition->execute([$proposition_id]);
        $update_proposition->fetch();
        // Sauvegarder le vote de la personne
        $insert_vote = $bdd->prepare("INSERT INTO `votes` (`semaine`, `membre`, `proposition`, `vote`) VALUES (?,?,?,?)");
        $insert_vote->execute([$id_current_semaine, $id_utilisateur_connecte, $proposition_id, $film_vote]);
  }
}

header('Location: index.php');
exit();
?>