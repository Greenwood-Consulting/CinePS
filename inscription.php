<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscription</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="theme_signup/colorlib-regform-9/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="theme_signup/colorlib-regform-9/css/style.css">
</head>
<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
if(isset($_POST['new_membre'])){//Ajout nouveau membre
    $nom_de_famille = addslashes($_POST['name']);
    $prenom = addslashes($_POST['prenom']);
    $mail = addslashes($_POST['email']);
    $ajout_membre = $bdd->query("INSERT INTO `membre` (`Nom`, `Prenom`, `mail`) VALUES ('".$nom_de_famille."','".$prenom."','".$mail."')");
    
}


?>
<body>

    <div class="main">

        <div class="container">
            <div class="signup-content">
                <form method="POST" id="signup-form" class="signup-form" action="">
                    <h2>Inscription</h2>
                    <div class="form-group">
                        <input type="text" class="form-input" name="name"  placeholder="Nom de famille"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-input" name="prenom" placeholder="Prenom"/>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-input" name="email" placeholder="email"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="new_membre" class="form-submit submit" value="Inscription">
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="theme_signup/colorlib-regform-9/vendor/jquery/jquery.min.js"></script>
    <script src="theme_signup/colorlib-regform-9/js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>