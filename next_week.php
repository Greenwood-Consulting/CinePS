<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

if(isset($_POST['new_proposeur'])){
    $nom_proposeur = $_POST['user'];
    $date_proposeur = $_POST['date'];
    $date_to_insert = date("Y-m-d", strtotime($date_proposeur));
    echo "proposeur".$nom_proposeur."<br/>";
    echo "date".$date_to_insert."<br/>";
    $ajout_proposeur = $bdd->query("INSERT INTO `semaine` (`jour`, `proposeur`, `proposition_termine`) VALUES ('".$date_to_insert."','".$nom_proposeur."','0')");
    echo"requete"."INSERT INTO `semaine` (`jour`, `proposeur`, `proposition_termine`) VALUES ('".$date_to_insert."','".$nom_proposeur."','0')";
}

$membres = $bdd->query('SELECT * FROM membre');
echo'<form method="post" action="">
        <label>Membres</label>
            <select class="text-dark" name="user">';
while($data = $membres->fetch()){ //Afficher un utlisateur
    echo"<option class='text-dark' value=".$data['Prenom'].">". $data['Nom']." ".$data['Prenom']."</option>";
}
echo"<input type='date' name='date'>";
echo"</select>
<button type='submit' name='new_proposeur'>Soumettre</button>
</form>";

?>