<?php 

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
    echo "  <button class='btn btn-warning login-btn' name='form_name' value='login'>Se connecter</button>";
    // si l'user n'est pas defini malgré la demande de login 
    if (($_POST['form_name'] ?? '') === 'login') {
        echo "<span>Le mdp n'est pas valide</span>";
    }
    echo "</div>
    </form>";
}


?>
