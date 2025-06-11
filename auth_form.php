<?php 
require_once 'includes/helpers.php';

//Si on vient du formulaire de connexion on sauvegrade l'utilisateur en session
// si l'utilisateur se présente

if(isset($_POST['user'])){//si l'utilisateur vient du formulaire de connexion

    $id_membre = $_POST['user'];
    $password = $_POST['password'];

    $json_user = ArrayUtils::find($membres, fn($m) => $m->id == $id_membre);

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
    $json_user = ArrayUtils::find($membres, fn($m) => $m->id == $_SESSION['user']);

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
                        echo"<option class='text-dark' value=".$user->id.">". $user->nom." ".$user->prenom."</option>";
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
