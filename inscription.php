<?php
require_once(__DIR__ . '/includes/init.php');


// ------------- reactions au formulaires ----------------------------

if(isset($_POST['new_membre'])){//Ajout nouveau membre
    $nom_de_famille = addslashes($_POST['name']);
    $prenom = addslashes($_POST['prenom']);
    $mail = addslashes($_POST['email']);

    // @TODO: add new membre

    // redirection
    header('Location: ' . base_url('inscription.php', true, 303));
    exit;
}

// ------------- fin des reactions au formulaires ----------------------------


require_once(__DIR__ . '/includes/header.php'); ?>

    <title>Inscription</title>

    <!-- Font Icon -->
    <!-- <link rel="stylesheet" href="<?= base_url('theme_signup/colorlib-regform-9/fonts/material-icon/css/material-design-iconic-font.min.css') ?>"> -->

    <!-- Main css -->
    <link rel="stylesheet" href="<?= base_url('theme_signup/colorlib-regform-9/css/style.css') ?>">
</head>
<body>

    <div class="main">

        <div class="container">
            <div class="signup-content">
                <form method="POST" id="signup-form" class="signup-form" action="<?= base_url('inscription.php') ?>">
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
    <!-- <script src="<?= base_url('theme_signup/colorlib-regform-9/vendor/jquery/jquery.min.js') ?>"></script> -->
    <!-- <script src="<?= base_url('theme_signup/colorlib-regform-9/js/main.js') ?>"></script> -->
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>