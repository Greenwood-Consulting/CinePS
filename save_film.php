<?php

$film =$_POST['film'];
$date = date('Y-M-d');
// On se connecte à la base
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
// On écrit la requête
 $sql= "INSERT INTO `film`(`titre`,`date`) VALUES (?, ?)";

// On prépare la requête
$query = $bdd->prepare($sql);

// On exécute la requête
if(!$query->execute([$film, $date]))
{
    die("Une erreur est survenue");
}
echo "$film a été ajouté";
//On recupère l'id
/*$id = $dbb->lastInsertId();
die("Le film est ajouté sous le numéro $id");*/

?>
<a href="propose_film.php"><button>Encore des films à proposer ?</button></a>
<a href="index.php"><button>Voir la liste</button></a>