<?php

$film = htmlspecialchars($_POST['film']);
// On se connecte à la base
$bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
// On écrit la requête
 $sql= "INSERT INTO `film`(`titre`) VALUES (".$film.")";

// On prépare la requête
$query = $bdd->prepare($sql);

// On exécute la requête
if(!$query->execute(array($film))){
    die("Une erreur est survenue");
};

//On recupère l'id
/*$id = $dbb->lastInsertId();
die("Le film est ajouté sous le numéro $id");*/

?>