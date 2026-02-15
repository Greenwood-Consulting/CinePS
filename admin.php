<?php
require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/common.php');

// ------------- reactions au formulaires ----------------------------

//Ajout nouveau membre si on a cliqué sur le bouton d'inscription
if(isset($_POST['new_membre'])){
    $nom_de_famille = addslashes($_POST['name']);
    $prenom = addslashes($_POST['prenom']);
    $mail = addslashes($_POST['email']);

    $array_membre = array(
        "nom" => $nom_de_famille,
        "prenom" => $prenom,
        "mail" => $mail,
        // TODO  le front ne devrait pas connaitre le mdp par defaut
        // Ca devrait être a la charge de l'api de gerer les propriétés par defaut.
        "mdp" => "Toto",
        "actif" => true
    );
    $json_membre = json_encode($array_membre);
    call_API("/api/newmembre", "POST", $json_membre);

    header('Location: ' . base_url('admin.php'));
    exit;
}

// Active ou desactive un membre
if(isset($_POST['enable_membre']) || isset($_POST['disable_membre'])){

    if(isset($_POST['enable_membre'])) {
        $membreId = $_POST['enable_membre'];
        $body = json_encode(array("actif" => true));
    } else {
        $membreId = $_POST['disable_membre'];
        $body = json_encode(array("actif" => false));
    }

    call_API("/api/actifMembre/".$membreId, "PATCH", $body);

    header('Location: ' . base_url('admin.php'));
    exit;
}

// ------------- fin des reactions au formulaires ----------------------------
    
require_once(__DIR__ . '/includes/header.php');
?>
    <link rel="stylesheet" href="<?= base_url('admin.css') ?>">
    <title>Administration</title>
</head>

<body>

    <h1 class="page__title">
        <img src="<?= base_url('assets/images/no_mojito.png') ?>" />
        No Mojito Zone
        <img src="<?= base_url('assets/images/no_mojito.png') ?>" />
    </h1>

    <h2>Inscription</h2>
    <form method="POST" id="signup-form" class="" action="<?= base_url('admin.php') ?>">
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

    call_API("/api/newSemaine", "POST", $json_semaine);
}

?>

<!-- Formulaire de création de semaine -->
<form method="post" action="<?= base_url('admin.php') ?>">

    <!-- Membre proposeur -->
    <label>Membres</label>
    <select class="text-dark" name="user">
        <?php foreach($membres as $membre): ?>
            <option class="text-dark" value="<?= htmlspecialchars($membre->id) ?>" >
                <?= htmlspecialchars($membre->nom . ' ' . $membre->prenom) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br/>

    <label>Date</label>
    <input type="date" name="date">
    <br/>

    <label>Type de PS</label>
    <select class="text-dark" name="typeSemaine">
        <option class="text-dark" value="PSAvecFilm">PS avec film</option>
        <option class="text-dark" value="PSSansFilm">PS sans film</option>
        <option class="text-dark" value="PasDePS">Pas de PS</option>
        <option class="text-dark" value="PSDroitDivin">PS de droit divin</option>
    </select>
    <br/>

    <button type="submit" name="new_proposeur">Créer une semaine</button>
</form>

<h2>Prochaines Semaine</h2>

<?php printNextproposeurs($id_current_semaine) ?>

<ol>
    <li>tokar</li> 
    <li>pilou</li> 
    <li>olivier</li> 
    <li>fred</li> 
    <li>renaud</li> 
    <li>bebert</li> 
    <li>marion</li> 
    <li>royale</li> 
    <li>grim</li> 
</ol>

<form method="POST" action="<?= base_url('admin.php') ?>">
    <table>
        <?php foreach($membres as $membre): ?>
            <tr>
                <td>
                    <span class="actif-name"><?= $membre->actif ? "✅" : "❌" ?> <?= $membre->nom ?></span>
                </td>
                <td>
                    <?php if($membre->actif): ?>
                        <button type="submit" class="actif" name="disable_membre" value="<?= $membre->id ?>" >Désactiver</button>
                    <?php else: ?>
                        <button type="submit" class="inactif" name="enable_membre" value="<?= $membre->id ?>" >Activer</button>
                    <?php endif; ?>
                </td>
            </tr>     
        <?php endforeach; ?>
    </table>
</form>

<?php require_once(__DIR__ . '/includes/footer.php'); ?>

</body>
</html>