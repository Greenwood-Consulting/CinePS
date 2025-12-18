<?php 

//Si on vient du formulaire de connexion on sauvegarde l'utilisateur en session
// si l'utilisateur se présente

if(isset($_POST['user'])){//si l'utilisateur vient du formulaire de connexion
    $body = json_encode([
        'email' => $_POST['user'],
        'password' => $_POST['password']
    ]);

    $response = call_API('/api/membre_login_check', 'POST', $body);

    if(isset($response) && !isset($response->error)){//Le mot de passe correspond on autorise la connection
        $_SESSION['user'] = $response->membre_id;     
    }else{//sinon on refuse la connection
        echo 'Le mdp n\'est pas valide';
    } 
}

if(isset($_SESSION['user'])){ //Si on est connecté on propose la déconnexion
    // @TODO : ne pas utiliser $membres, pour gérer l'authentification, à refactoriser quand on refactorisera l'Authentification
    $json_user = array_values(array_filter($membres, fn($m) => $m->id == $_SESSION['user']))[0] ?? null;

    ?>
    <div class="login-form">
        <ul class="menu">
            <li class="has-submenu">
                <button type="button" class="menu-button">
                    <?php echo $json_user->nom; ?>
                    <!-- Petit triangle en SVG pour indiquer qu'il y a un sous-menu -->
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <ul class="submenu">
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="pre_selections.php">Pré-Sélections</a></li>
                    <li><a href="deconnexion.php">Se déconnecter</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <?php
}
else{ //Sinon on propose la connexion
    echo "";
    echo'<form method="post" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" class="login-form">';
    echo '<div class="form-group">
            <div class="fields">';
    echo '      <div class="field-group">
                    <label for="user">Membres</label>
                    <select class="text-dark" name="user" id="user">';
                    foreach($membres as $user){ //Afficher un utlisateur
                        echo"<option class='text-dark' value=".$user->mail.">". $user->nom." ".$user->prenom."</option>";
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


?>
