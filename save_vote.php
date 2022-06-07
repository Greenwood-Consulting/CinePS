<?php
include('header.php');
include('common.php');

$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

// Mise à jour des scroes de tous les films
foreach($_POST as $proposition_id=>$film_vote){
    $get_proposition = $bdd->query("SELECT * FROM proposition WHERE id = '".$proposition_id."'");
    $current_proposition = $get_proposition->fetch();
    $new_score= $current_proposition['score'] - $film_vote;
    $update_proposition = $bdd->query('UPDATE proposition SET score='.$new_score.' WHERE id='.$proposition_id);
    $update_proposition->fetch();
}

// Mise à jour de la table a_vote pour l'utilisateur connecté
$user= $bdd->query("SELECT id FROM membre WHERE Prenom = '".$_SESSION['user']. "'");
$id_utlisateur_connecte = $user->fetch()['id'];
$insert_a_vote = $bdd->query("INSERT INTO `a_vote` (`id`, `votant`, `semaine`) VALUES ('', '".$id_utlisateur_connecte."','".$id_current_semaine."')");


echo 'Votre vote a été enregistré !';

header('Location ; index.php')
?>