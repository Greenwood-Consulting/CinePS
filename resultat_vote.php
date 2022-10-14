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
echo "<h1 id = 'titre'>Résultat du vote</h1>";
echo "<a href=index.php><button type='button' class='btn btn-warning'>Revenir</button></a>";
  $deb= new DateTime ("Fri 12:00");
  $fin = new DateTime("Mon 14:00");
  $curdate=new DateTime();
  $vote_period=($curdate>=$deb && $curdate <= $fin);

  if($vote_period){
    echo "<div id = 'tableau'>";
    printChoixvote($id_current_semaine);
    echo "</div>";
  }
  else{
    echo "<h1 class = 'prout'>Le vote n'est pas terminé, vous ne pouvez donc pas voir les résultats du vote.</h1>";
  }

?>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
<p>x</p></br>
</body>
</html>


