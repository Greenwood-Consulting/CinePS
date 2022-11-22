<?php
include('common.php');
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

//Construction du tableau data_année
$data_annee = [];
$get_film_annee = $bdd->query("SELECT sortie_film, COUNT(id) AS nb_films FROM film GROUP BY sortie_film");

while($film_annee = $get_film_annee->fetch()){
    array_push($data_annee, array("Année Film" => $film_annee['sortie_film'], "nombre" => $film_annee['nb_films']));
}

$count_data_annee = count($data_annee);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawMaterial);

    function drawMaterial() {
      //draw data_annee
      var data_annee = new google.visualization.DataTable();
      data_annee.addColumn('string', 'Année Film');
      data_annee.addColumn('number', 'nombre');

      data_annee.addRows([
        <?php
          for($i=0;$i<$count_data_annee;$i++){
            echo "['" . $data_annee[$i]['Année Film'] . "'," . $data_annee[$i]['nombre'] . "],";
          } 
        ?>
      ]);

      var materialOptions = {
        chart: {
          title: 'Nombre de films par année'
        },
        hAxis: {
          title: 'nombre',
          minValue: 0,
        },
        vAxis: {
          title: 'Année Film'
        },
        bars: 'horizontal'
      };
      var materialChart = new google.charts.Bar(document.getElementById('chart_film_année'));
      materialChart.draw(data_annee, materialOptions);
    }
</script>
</head>
<body>
    <div id="chart_film_année"></div>
</body>
</html>