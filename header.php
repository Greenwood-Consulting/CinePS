<?php
session_start();
//Si on vient du formulaire de connexion on sauvegrade l'utilisateur en session
if(isset($_POST['user'])){
    $_SESSION['user']=$_POST['user'];
}
if(isset($_SESSION['user'])){ //Si on est connecté on propose la déconnexion
    echo "Utilisateur connecté : ".$_SESSION['user'];
    echo "<a href='deconnexion.php'><button type='button' class='btn btn-warning'>Se deconnecter</button></a>";
}
else{ //Sinon on propose la connexion
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete = $bdd->query('SELECT * FROM Membre');
    echo'<form method="post" action="propose_film.php">
            <label>Membres</label>
                <select name="user">';
    while($data = $requete->fetch()){ //Afficher un utlisateur
        echo"class='btn btn-warning' <option value=".$data['Prenom'].">". $data['Nom']." ".$data['Prenom']."</option>";
    }
    echo"</select>
    <button type='submit' class='btn btn-warning'>Se connecter</button>
    </form>'";
}
$date = date('l-j-M-y h:i:s');
echo $date;
?>
<hr/>