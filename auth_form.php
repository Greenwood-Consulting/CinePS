<?php 

//Si on vient du formulaire de connexion on sauvegrade l'utilisateur en session
// si l'utilisateur se présente

if(isset($_POST['user'])){//si l'utilisateur vient du formulaire de connexion

    $id_membre = $_POST['user'];
    $password = $_POST['password'];

    $json_user = call_API("/api/membres/".$id_membre, "GET");

    if(! empty($json_user)){//On vérifie qui'il y ait un mdp pour l'utilisateur connecté
        //$password = hash('sha256', $password);
        // @TODO : à revoir, je comprends pas tout

        if($json_user->mdp == $password){//Le mot de passe correspond on autorise la connection
            $_SESSION['user'] = $json_user->id;     
        }else{//sinon on refuse la connection
            echo 'Le mdp n\'est pas valide';
        } 
    }else{//on refuse la connection
        echo 'Cet utilisateur n\'existe pas dans la base de données';
    } 
}

if(isset($_SESSION['user'])){ //Si on est connecté on propose la déconnexion
    $json_user = call_API("/api/membres/".$_SESSION['user'], "GET");
    // @todo: remplacer le style par une classe CSS (à faire quand le fichier CSS sera refactorisé)
    echo "<div class=\"login-form\">
            Utilisateur connecté : <a href='profil.php' style='color: gold;'>".$json_user->Nom."</a>";
    echo "<a href = 'deconnexion.php'><button name='deconnexion' type='button' class='btn btn-warning '>Se deconnecter</button></a>
        </div>";
}
else{ //Sinon on propose la connexion
    $json_users = call_API("/api/membres", "GET");

    echo "";
    echo'<form method="post" action="index.php" class="login-form">';
    echo '<div class="form-group">
            <div class="fields">';
    echo '      <div class="field-group">
                    <label for="user">Membres</label>
                    <select class="text-dark" name="user" id="user">';
                    foreach($json_users as $user){ //Afficher un utlisateur
                        echo"<option class='text-dark' value=".$user->id.">". $user->Nom." ".$user->Prenom."</option>";
                    }
    echo "           </select>
                </div>
                ";

    /*Password input */
    echo    '   <div class="field-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" />
                </div>
            </div>';
    
    // Bouton de connexion
    echo "  <button class='btn btn-warning login-btn' name='connect'>Se connecter</button>
        </div>
    </form>";
}
echo "</br>";


?>
