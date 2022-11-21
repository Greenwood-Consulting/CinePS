<?php
include('common.php');
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
//Construction du tableau data_score
$data_score = [];
$get_film_semaine= $bdd->query("SELECT film, score  FROM proposition WHERE semaine = '".$id_current_semaine."'");

while($film_semaine = $get_film_semaine->fetch()){
  $get_titre_film = $bdd->query("SELECT titre FROM film WHERE id = ".$film_semaine['film']);
  $titre_film = $get_titre_film->fetch()['titre'];
  array_push($data_score, array("Film" => $titre_film, "Score" => $film_semaine['score']));
}

$count_data_score = count($data_score);



//construction du tableau data_proposeur
$data_proposeurs = [];
$get_proposeurs = $bdd->query("SELECT proposeur, COUNT(id) AS nb_proposeurs FROM semaine GROUP BY proposeur");

while($proposeurs = $get_proposeurs->fetch()){
  echo "proposeur". $proposeurs['proposeur'];
  echo "<br/>";
  echo "nb_proposeurs". $proposeurs['nb_proposeurs'];
  echo "<br/>";
  array_push($data_proposeurs, array("Proposeur" => $proposeurs['proposeur'], "nombre" => $proposeurs['nb_proposeurs']));
}

$count_data_proposeurs = count($data_proposeurs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawMaterial);

    function drawMaterial() {
      //draw data_score
      var data_score = new google.visualization.DataTable();
      data_score.addColumn('string', 'Film');
      data_score.addColumn('number', 'Score');

      data_score.addRows([
        <?php
          for($i=0;$i<$count_data_score;$i++){
            echo "['" . $data_score[$i]['Film'] . "'," . $data_score[$i]['Score'] . "],";
          } 
        ?>
      ]);

      var materialOptions = {
        chart: {
          title: 'Classement du vote'
        },
        hAxis: {
          title: 'Score',
          minValue: 0,
        },
        vAxis: {
          title: 'Film'
        },
        bars: 'horizontal'
      };
      var materialChart = new google.charts.Bar(document.getElementById('chart_div'));
      materialChart.draw(data_score, materialOptions);
      
      //draw data_proposeurs
      var data_proposeurs = new google.visualization.DataTable();
      data_proposeurs.addColumn('string', 'proposeurs');
      data_proposeurs.addColumn('number', 'nombre');

      data_proposeurs.addRows([
        <?php
          for($i=0;$i<$count_data_proposeurs;$i++){
            echo "['" . $data_proposeurs[$i]['Proposeur'] . "'," . $data_proposeurs[$i]['nombre'] . "],";
          } 
        ?>
      ]);

      var materialOptions = {
        chart: {
          title: 'nombre de propositions'
        },
        hAxis: {
          title: 'nombre',
          minValue: 0,
        },
        vAxis: {
          title: 'proposeurs'
        },
        bars: 'horizontal'
      };
      var materialChart = new google.charts.Bar(document.getElementById('chart_proposeurs'));
      materialChart.draw(data_proposeurs, materialOptions);
    }
</script>
</head>
<body>
    <h2>Graphique</h2>
    <div id="chart_div"></div>
    <div id="chart_proposeurs"></div>
</body>
</html>
