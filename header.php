<?php
session_start();

//Si on vient du formulaire de connexion on sauvegrade l'utilisateur en session
// si l'utilisateur se présente

if(isset($_POST['user'])){//si l'utilisateur est connecté

    $id_membre = $_POST['user'];
    $password = $_POST['password'];

    $user = callAPI("/api/membres/".$id_membre);
    $array_user = json_decode($user);

    if(! empty($array_user)){//On vérifie qui'il y est un mdp pour l'utilisateur connecté
        //$password = hash('sha256', $password);

        if($array_user->mdp == $password){//Le mot de passe correspond on autorise la connection
            $_SESSION['user'] = $array_user->id;     
        }else{//sinon on refuse la connection
            echo 'Le mdp n\'est pas valide';
        } 
    }else{//on refuse la connection
        echo 'Cet utilisateur n\'existe pas dans la base de données';
    } 
}

if(isset($_SESSION['user'])){ //Si on est connecté on propose la déconnexion
    echo "Utilisateur connecté : ".$_SESSION['user'];
    echo "<a href = 'deconnexion.php'><button name='deconnexion' type='button' class='btn btn-warning '>Se deconnecter</button></a>";
}
else{ //Sinon on propose la connexion
    $users = callAPI("/api/membres");
    $array_users = json_decode($users);

    echo'<form method="post" action="index.php">
    <label>Membres</label>
        <select class="text-dark" name="user">';
    foreach($array_users as $user){ //Afficher un utlisateur
        echo"<option class='text-dark' value=".$user->id.">". $user->Nom." ".$user->Prenom."</option>";
    }
    echo"</select>";

    /*Password input */
    echo    '<div class="form-outline mb-4">
                <input type="password" name="password" class="form-control" />
                <label class="form-label" for="form2Example2">Password</label>
            </div>';
    echo "<button class='btn btn-warning' name='connect'>Se connecter</button>
    </form>";
}
echo "</br>";
echo "<a href='historique_film.php'><button type='button' class='btn btn-warning'>Historique</button></a>";
echo "<a href='stat_barre.php'><button type='button' class='btn btn-warning'>Statistique</button></a>";

?>
<hr/>