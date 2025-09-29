<?php
include('includes/init.php');
include('common.php');
include('calcul_etat.php');
include('header.php');
?>

    <link href="resultat_vote.css" rel="stylesheet">
    <title>Résultat du vote de la semaine</title>
</head>
<body>
<?php
echo "<h1 id = 'titre'>Résultat du vote</h1>";
echo "<a href=index.php><button type='button' class='btn btn-warning'>Revenir</button></a>";
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


