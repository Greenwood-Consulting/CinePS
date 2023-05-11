<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
include('common.php');

//Construction du tableau data_score
$data_score = [];
$get_film_semaine = $bdd->prepare("SELECT film, score FROM proposition WHERE semaine = ?");
$get_film_semaine->execute([$id_current_semaine]);

while($film_semaine = $get_film_semaine->fetch()){
  $get_titre_film = $bdd->prepare("SELECT titre FROM film WHERE id = ?");
  $get_titre_film->execute([$film_semaine['film']]);
  $titre_film = $get_titre_film->fetch()['titre'];
  array_push($data_score, array("Film" => $titre_film, "Score" => $film_semaine['score']));
}

$count_data_score = count($data_score);




//construction du tableau data_proposeur
$data_proposeurs = [];
$get_proposeurs = $bdd->query("SELECT proposeur, COUNT(id) AS nb_proposeurs FROM semaine  WHERE `proposition_termine` = 1 GROUP BY proposeur");

while($proposeurs = $get_proposeurs->fetch()){
  array_push($data_proposeurs, array("Proposeur" => $proposeurs['proposeur'], "nombre" => $proposeurs['nb_proposeurs']));
}

$count_data_proposeurs = count($data_proposeurs);


//Construction du tableau data_année
$data_annee = [];
$get_film_annee = $bdd->query("SELECT sortie_film FROM film");

$films_par_decennie = [];



while($film = $get_film_annee->fetch()){
  $date_sortie = $film['sortie_film'];
  $decennie = intdiv($date_sortie, 10)*10;
  if(isset($films_par_decennie[$decennie])){
    $nb_films = $films_par_decennie[$decennie];
    $films_par_decennie[$decennie] = $nb_films + 1;
  }else{
    $films_par_decennie[$decennie] = 1;
  }
}
foreach($films_par_decennie as $decennie => $nb_films){
  array_push($data_annee, array("Année Film" => $decennie, "nombre" => $nb_films));
}

$count_data_annee = count($data_annee);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistique</title>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawMaterial);
    google.charts.setOnLoadCallback(drawChart);

    function drawMaterial() {
      //draw data_score
      var data_score = new google.visualization.DataTable();
      data_score.addColumn('string', 'Film');
      data_score.addColumn('number', '');

      data_score.addRows([
        <?php
          for($i=0;$i<$count_data_score;$i++){
            echo "['" . $data_score[$i]['Film'] . "'," . $data_score[$i]['Score'] . "],";
          } 
        ?>
      ]);

      var materialOptions = {
        chart: {
          title: ''
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

      //draw data_annee
      var data_annee = new google.visualization.DataTable();
      data_annee.addColumn('string', 'Année Film');
      data_annee.addColumn('number', '');

      data_annee.addRows([
        <?php
          for($i=0;$i<$count_data_annee;$i++){
            echo "['" . $data_annee[$i]['Année Film'] . "'," . $data_annee[$i]['nombre'] . "],";
          } 
        ?>
      ]);

      var materialOptions = {
        chart: {
          title: ''
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

    function drawChart(){
      //draw data_proposeurs
      var data_proposeurs = new google.visualization.DataTable();
      data_proposeurs.addColumn('string', 'proposeurs');
      data_proposeurs.addColumn('number', 'nombre');

      data_proposeurs.addRows([
        <?php
          for($i=0;$i<$count_data_proposeurs;$i++){
            echo "['" . addslashes($data_proposeurs[$i]['Proposeur']) . "'," . $data_proposeurs[$i]['nombre'] . "],";
          } 
        ?>
      ]);

      var options = {
          title: ''
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data_proposeurs, options);
    };
</script>
</head>
<body>
  <a href=index.php><button type='button'>Accueil</button></a>
  <h2>Classement Des films de la semaine
  <div id="chart_div"  style="width: 1800px; height: 200px"></div>
  </h2>
  <h2> Films par années
  <div id="chart_film_année" style="width: 1800px; height: 200px"></div>
  </h2>
  <h2> Nombre de fois que les membres ont été proposeurs
  <div id="piechart" style="width: 900px; height: 500px"></div>
  </h2>
</body>
</html>
