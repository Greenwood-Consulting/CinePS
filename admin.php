<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Admin</title>
</head>
<body>
    <h2 class="container-fluid p-5 bg-primary text-white text-center">Inscription</h2>
    <?php
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    if(isset($_POST['new_membre'])){//Ajout nouveau membre
        $nom_de_famille = $_POST['name'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['email'];
        $ajout_membre = $bdd->query("INSERT INTO `membre` (`Nom`, `Prenom`, `mail`) VALUES ('".$nom_de_famille."','".$prenom."','".$mail."')");
        
    }
?>

    
        <form method="POST" id="signup-form" class="" action="">
            <div class="col">
                <input type="text" class="" name="name"  placeholder="Nom de famille"/>
            </div>
            <div class="col">
                <input type="text" class="" name="prenom" placeholder="Prenom"/>
            </div>
            <div class="col">
                <input type="email" class="" name="email" placeholder="email"/>
            </div>
            <div class="">
                <input type="submit" name="new_membre" class="form-submit submit" value="Inscription">
            </div>
        </form>
</br>
</br>
<h2 class="container-fluid p-5 bg-secondary text-white text-center"> Choix du proposeur pour la semaine souhaitée </h2>
<?php
if(isset($_POST['new_proposeur'])){
    $nom_proposeur = $_POST['user'];
    $date_proposeur = $_POST['date'];
    $date_to_insert = date("Y-m-d", strtotime($date_proposeur));
    $ajout_proposeur = $bdd->query("INSERT INTO `semaine` (`jour`, `proposeur`, `proposition_termine`) VALUES ('".$date_to_insert."','".$nom_proposeur."','0')");
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

</body>
</html>