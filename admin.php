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
    include('common.php');
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    if(isset($_POST['new_membre'])){//Ajout nouveau membre si on a cliqué sur le bouton d'inscription
        $nom_de_famille = addslashes($_POST['name']);
        $prenom = addslashes($_POST['prenom']);
        $mail = addslashes($_POST['email']);

        $array_membre = array(
            "nom" => $nom_de_famille,
            "prenom" => $prenom,
            "mail" => $mail,
            "mdp" => "Toto"
        );
        $json_membre = json_encode($array_membre);
        $membre = callAPI_POST("/api/newmembre", $json_membre);
        $new_membre = json_decode($membre);
   
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
//si il clique sur le bouton new_proposeur
if(isset($_POST['new_proposeur'])){
    $id_proposeur = addslashes($_POST['user']);
    $date_proposeur = addslashes($_POST['date']);
    $date_to_insert = date("Y-m-d", strtotime($date_proposeur));
    // $ajout_proposeur = $bdd->prepare("INSERT INTO `semaine` (`jour`, `proposeur`, `proposition_termine`, `theme`) VALUES (?,?,?,?)");

    $array_semaine = array(
        "proposeur_id" => $id_proposeur,
        "jour" => $date_proposeur,
        "proposition_termine" => false,
        "theme" => ""
    );
    $json_semaine = json_encode($array_semaine);

    $semaine = callAPI_POST("/api/newSemaine", $json_semaine);
    $new_semaine = json_decode($semaine);
    echo "<pre>";
    print_r($new_semaine);
    echo "</pre>";

}
//$membres = $bdd->query('SELECT * FROM membre');
$membres_API = callAPI("/api/membres");
$decode_membre = json_decode($membres_API);
echo'<form method="post" action="">
        <label>Membres</label>
            <select class="text-dark" name="user">';
            
foreach($decode_membre as $membre){ //Afficher un utlisateur dans le dropdown
    echo"<option class='text-dark' value=".$membre->id.">". $membre->Nom." ".$membre->Prenom."</option>";
}
echo"<input type='date' name='date'>";
echo"</select>
<button type='submit' name='new_proposeur'>Soumettre</button>
</form>";
printNextproposeurs($id_current_semaine);
echo "<p class = 'text-center'><b>tokar <br/> pilou <br/> olivier <br/> fred <br/> renaud <br/> bebert <br/> marion <br/> royale <br/> grim</b></p>";

?>

</body>
</html>