<?php
session_start();
if(isset($_POST['user'])){
    $_SESSION['user']=$_POST['user'];
}
if(isset($_SESSION['user'])){
    echo "Utilisateur connecté : ".$_SESSION['user'];
    echo "<a href='deconnexion.php'><button>Se deconnecter</button></a>";
}else{
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete = $bdd->query('SELECT * FROM Membre');
    echo'<form method="post" action="save_film.php">
            <label>Membres</label>
                <select name="user">';
    while($data = $requete->fetch()){
        echo"<option value=".$data['Prenom'].">". $data['Nom']." ".$data['Prenom']."</option>";
    }
echo'</select>
<button type="submit">Se connecter</button>
</form>';
}





echo "<hr>";
$film =$_POST['film'];
$date = date('Y-m-d');
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