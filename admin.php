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
//si il clique sur le bouton new_proposeur, création d'une nouvelle semaine
if(isset($_POST['new_proposeur'])){
    $id_proposeur = addslashes($_POST['user']);
    $date_proposeur = addslashes($_POST['date']);
    $date_to_insert = date("Y-m-d", strtotime($date_proposeur));
    $type_semaine = $_POST['typeSemaine'];

    $array_semaine = array(
        "proposeur_id" => $id_proposeur,
        "jour" => $date_proposeur,
        "type_semaine" => $type_semaine,
        "proposition_termine" => false,
        "theme" => "",
        "type" => $typeSemaine
    );
    $json_semaine = json_encode($array_semaine);

    $semaine = callAPI_POST("/api/newSemaine", $json_semaine);
    $new_semaine = json_decode($semaine);

}
$membres_API = callAPI("/api/membres");
$decode_membre = json_decode($membres_API);
echo '<form method="post" action="">';

// Membre proposeur
echo '  <label>Membres</label>
        <select class="text-dark" name="user">';
            foreach($decode_membre as $membre){ //Afficher un utlisateur dans le dropdown
                echo"<option class='text-dark' value=".$membre->id.">". $membre->Nom." ".$membre->Prenom."</option>";
            }
echo "  </select>";
echo "  <br/>";

// Date de la semaine
echo "  <label>Date</label>";
echo "  <input type='date' name='date'>";
echo "  <br/>";

// Type de semaine
echo "  <label>Type de PS</label>";
echo '  <select class="text-dark" name="typeSemaine">';
echo "       <option class='text-dark' value='AvecFilm'>PS avec film</option>";
echo "       <option class='text-dark' value='PSSansFilm'>PS sans film</option>";
echo "       <option class='text-dark' value='PasDePS'>Pas de PS</option>";
echo "  </select>";
echo "  <br/>";

// Submit
echo "<button type='submit' name='new_proposeur'>Créer une semaine</button>
</form>";

echo "<h2>Prochaines Semaine</h2>";

printNextproposeurs($id_current_semaine);
echo "<p class = 'text-center'><b>tokar <br/> pilou <br/> olivier <br/> fred <br/> renaud <br/> bebert <br/> marion <br/> royale <br/> grim</b></p>";

?>

</body>
</html>