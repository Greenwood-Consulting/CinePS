<?php 

//Si on vient du formulaire de connexion on sauvegrade l'utilisateur en session
// si l'utilisateur se présente

if(isset($_POST['user'])){//si l'utilisateur vient du formulaire de connexion

    $id_membre = $_POST['user'];
    $password = $_POST['password'];

    $user = call_API_GET("/api/membres/".$id_membre);
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
    $user = call_API_GET("/api/membres/".$_SESSION['user']);
    $array_user = json_decode($user);
    ?>
    <div class="login-menu">
        <div class="logged-user-button"><? echo $array_user->Nom ?> <span class="arrow">▾</span></div>
        <div class="dropdown">
            <a href="profil.php" class="dropdown-item">Profil</a>
            <a href="deconnexion.php" class="dropdown-item">Se déconnecter</a>
        </div>
    </div>
    <?
} else { //Sinon on propose la connexion
    $users = call_API_GET("/api/membres");
    $array_users = json_decode($users);

    echo "";
    echo'<form method="post" action="index.php" class="login-form">';
    echo '<div class="form-group">
            <div class="fields">';
    echo '      <div class="field-group">
                    <label for="user">Membres</label>
                    <select class="text-dark" name="user" id="user">';
                    foreach($array_users as $user){ //Afficher un utlisateur
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
