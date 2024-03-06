<?php
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="resultat_vote.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<?php
include('common.php');
include('calcul_etat.php');
echo "<h1 id = 'titre'>Résultat du vote</h1>";
echo "<a href=index.php><button type='button' class='btn btn-warning'>Revenir</button></a>";
  $deb= new DateTime ("Fri 16:00");
  $fin = new DateTime("Mon 8:00");
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


