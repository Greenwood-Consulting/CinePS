<?php
require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/common.php');
require_once(__DIR__ . '/includes/calcul_etat.php');
require_once(__DIR__ . '/includes/header.php');
?>

    <link href="<?= base_url('resultat_vote.css') ?>" rel="stylesheet">
    <title>Résultat du vote de la semaine</title>
</head>
<body>
  <h1 id="titre">Résultat du vote</h1>
  <a href="<?= base_url('index.php') ?>"><button type="button" class="btn btn-warning">Revenir</button></a>
  <?php
  if($vote_termine_cette_semaine){// On affiche le résultat du vote Si le vote est terminé (car tout le monde a votéo) ou si la période de vote est terminée
    echo "<div id = 'tableau'>";
    printChoixvote($id_current_semaine);
    echo "</div>";
  }
  else{//Sinon le tableau n'est pas crée
    echo "<h1 class = 'message_vote'>Le vote n'est pas terminé, vous ne pouvez donc pas voir les résultats du vote.</h1>";
  }

?>

</body>
</html>


