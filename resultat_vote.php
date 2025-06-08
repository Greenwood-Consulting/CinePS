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
  $deb= new DateTime ("Fri 16:00");
  $fin = new DateTime(FIN_PERIODE_VOTE);
  $curdate=new DateTime();
  $watch_period=($curdate>=$deb && $curdate <= $fin);

  if($watch_period || $vote_termine_cette_semaine){//Si le vote est terminé on affiche les résultats des votes de chaque users sous forme de tableau
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


