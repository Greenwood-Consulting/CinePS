<?php
include('common.php');
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
$data_score = [];
$get_film_semaine= $bdd->query("SELECT film, score  FROM proposition WHERE semaine = '".$id_current_semaine."'");

while($film_semaine = $get_film_semaine->fetch()){
  $get_titre_film = $bdd->query("SELECT titre FROM film WHERE id = ".$film_semaine['film']);
  $titre_film = $get_titre_film->fetch()['titre'];
  array_push($data_score, array("Film" => $titre_film, "Score" => $film_semaine['score']));
}

$count_data_score = count($data_score);
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
    }
</script>
</head>
<body>
    <h2>Graphique</h2>
    <div id="chart_div"></div>
</body>
</html>
