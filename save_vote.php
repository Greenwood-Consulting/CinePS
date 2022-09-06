<?php
include('header.php');
include('common.php');

$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');


// Mise à jour de la table a_vote pour l'utilisateur connecté
$user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
$id_utilisateur_connecte = $user->fetch()['id'];
$insert_a_vote = $bdd->query("INSERT INTO `a_vote` (`votant`, `semaine`) VALUES ('".$id_utilisateur_connecte."','".$id_current_semaine."')");


if(!isset($_POST['abstention'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
    foreach($_POST as $proposition_id=>$film_vote){// Mise à jour des scroes de tous les films
        $get_proposition = $bdd->query("SELECT * FROM proposition WHERE id = '".$proposition_id."'");
        $current_proposition = $get_proposition->fetch();
        $new_score= $current_proposition['score'] - $film_vote;
        $update_proposition = $bdd->query('UPDATE proposition SET score='.$new_score.' WHERE id='.$proposition_id);
        $update_proposition->fetch();
        // Sauvegarder le vote de la personne
        $insert_vote = $bdd->query("INSERT INTO `votes` (`semaine`, `membre`, `proposition`, `vote`) VALUES ('".$id_current_semaine."', '".$id_utilisateur_connecte."', '".$proposition_id."', '".$film_vote."')");
  }
}





echo 'utilisateur connecte' .$id_utilisateur_connecte. '<br/>' ;

echo 'Votre vote a été enregistré ! <a href=index.php><button>Revenir</button>';

?>