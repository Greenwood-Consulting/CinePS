<?php
include 'header.php';
include 'env.php';
include('common.php');
?>

    <link rel="stylesheet" href="admin.css">
    <title>Administration</title>
</head>

<body>
    <h2>Inscription</h2>
    <?php
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
<h2> Choix du proposeur pour la semaine souhaitée </h2>
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
        "type_semaine" => $type_semaine
    );
    $json_semaine = json_encode($array_semaine);

    $semaine = callAPI_POST("/api/newSemaine", $json_semaine);
    $new_semaine = json_decode($semaine);

}

//Formulaire de création de semaine
$membres_API = call_API_GET("/api/membres");
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
echo "       <option class='text-dark' value='PSAvecFilm'>PS avec film</option>";
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

$get_membres = call_API_GET("/api/membres");
$membres = json_decode($get_membres);


foreach($membres as $membre) {
    echo $membre->Nom;
    echo "<form method='post' action='admin.php'>";
    echo "<input type='hidden' name='id' value='$membre->id'>";
    echo "<button type='submit' name='actif' value='1' class='actif'>Activer</button>";
    echo "<button type='submit' name='actif' value='0' class='inactif'>Désactiver</button>";
    echo "</form>";
    echo "<br/><br/>";
}

if(isset($_POST['id']) && isset($_POST['actif'])){
    $id = $_POST['id'];
    $actif = $_POST['actif'];

    $statut_activation = array("actif" => $actif);
    $json_statut_activation = json_encode($statut_activation);

    callAPI_PATCH("/api/actifMembre/".$id, $json_statut_activation);
}
?>

</body>
</html>