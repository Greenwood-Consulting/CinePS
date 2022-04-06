<?php 
include('header.php');

$film =$_POST['film'];
$date = date('Y-m-d');
// On se connecte à la base
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
// On écrit la requête
 $sql= "INSERT INTO `film`(`titre`,`date`) VALUES (?, ?)";


$query = $bdd->prepare($sql);


if(!$query->execute([$film, $date]))
{
    die("Une erreur est survenue");
}
echo "$film a été ajouté";



?>
<a href="propose_film.php"><button>Encore des films à proposer ?</button></a>
<a href="index.php"><button>Voir la liste</button></a>