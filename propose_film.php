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
    echo'<form method="post" action="propose_film.php">
            <label>Membres</label>
                <select name="user">';
    while($data = $requete->fetch()){
        echo"<option value=".$data['Prenom'].">". $data['Nom']." ".$data['Prenom']."</option>";
    }
echo'</select>
<button type="submit">Se connecter</button>
</form>';
}
?>
<?php
if(!isset($_SESSION['user'])){
    echo "Vous devez être connecté pour voir cette page";
}else{
    ?>


<form method="POST" action="save_film.php">
    <!--label> Proposition de films:</label>
    <input type="text" name="film">
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <label> Proposition de films:</label>
    <input type="text" name="film"-->
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit">Ajouter un film</button>';

    ?>
</form>
<?php
}?>