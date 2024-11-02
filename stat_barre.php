<?php
include('common.php');

//Construction du tableau data_score
$data_score = [];
$score_film= callAPI("/api/filmsProposes/".$id_current_semaine);
$array_score_film = json_decode($score_film);
foreach($array_score_film as $film){
  array_push($data_score, array("Film" => $film->film->titre, "Score" => $film->score));

}
$count_data_score = count($data_score);

//construction du tableau data_proposeur
$data_proposeurs = [];
$get_proposeurs = callAPI("/api/getNbPropositionsParProposeur");
$array_proposeurs = json_decode($get_proposeurs);
foreach($array_proposeurs as $proposeurs){
  array_push($data_proposeurs, array("Proposeur" => $proposeurs->proposeur, "nombre" => $proposeurs->nb_semaines));
}

$count_data_proposeurs = count($data_proposeurs);


//Construction du tableau data_année
$data_annee = [];
$filmsGagnants = callAPI("/api/filmsGagnants");
$array_filmsGagnants = json_decode($filmsGagnants);
$films_par_decennie = [];
foreach($array_filmsGagnants as $film){
  $decennie = intdiv($film->sortie_film, 10)*10;
  if(isset($films_par_decennie[$decennie])){
    $nb_films = $films_par_decennie[$decennie];
    $films_par_decennie[$decennie] = $nb_films + 1;
  }else{
    $films_par_decennie[$decennie] = 1;
  }
}
krsort($films_par_decennie);
foreach($films_par_decennie as $decennie => $nb_films){
  array_push($data_annee, array("Décennie" => $decennie, "nombre" => $nb_films));
}

$count_data_annee = count($data_annee);


// while($film = $get_film_annee->fetch()){
//   $date_sortie = $film['sortie_film'];
//   $decennie = intdiv($date_sortie, 10)*10;
//   if(isset($films_par_decennie[$decennie])){
//     $nb_films = $films_par_decennie[$decennie];
//     $films_par_decennie[$decennie] = $nb_films + 1;
//   }else{
//     $films_par_decennie[$decennie] = 1;
//   }
// }
// foreach($films_par_decennie as $decennie => $nb_films){
//   array_push($data_annee, array("Année Film" => $decennie, "nombre" => $nb_films));
// }

// $count_data_annee = count($data_annee);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="historique_film.css" rel="stylesheet">

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
      data_annee.addColumn('string', 'Décennie');
      data_annee.addColumn('number', '');

      data_annee.addRows([
        <?php
          for($i=0;$i<$count_data_annee;$i++){
            echo "['" . $data_annee[$i]['Décennie'] . "'," . $data_annee[$i]['nombre'] . "],";
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
        title: '',
      };
      
      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
      chart.draw(data_proposeurs, options);
    };
</script>
</head>

<body>

  <div class="fixed-header">
    <div class="centered-buttons">
      <?php
      include('nav.php'); 
      ?>
    </div>
    <div class="right-form">
      <?php
      include('auth_form.php');
      ?>
    </div>
  </div>

  <div class="main-content">
    <h1 class="titre">Statistiques</h1>
    <h2>Classement Des films de la semaine</h2>
    <div id="chart_div"  style="width: 40%; height: 200px" class="main-zone stat-chart"></div>

    <h2> Films vus par décennie</h2>
    <div id="chart_film_année" style="width: 40%; height: 200px" class="main-zone"></div>
    
    <h2> Nombre de fois que les membres ont été proposeurs</h2>
    <div id="piechart" style="width: 40%; height: 500px;" class="main-zone" ></div>
    
    <h2>Le votant le plus satisfait</h2>

    <p class = "explication">
      <u>Explications sur le calcul du niveau de satisfaction :</u><br>
      on prend tous les films qui ont été vus en PS (donc pas les films proposés mais qui ont perdu le vote)
      et on calcule la moyenne du score donné par chaque utilisateur sur tous les films vu.
      Plus le score est bas, plus le film était haut dans les préférences de l'utilisateur.
      Plus le core est élevé, plus le film était bas dans les préférences de l'utilisateur.
      Donc une moyenne de scores bas indique qu'en général le vote de l'utilisateur est satisfait.
      Une moyenne de scores élevée indique que les films vus correspondaient moins aux choix de l'utilisateur.
    </p>

    <?php
    $satisfaction_data = callAPI("/api/usersSatisfaction");
    $array_satisfaction = json_decode($satisfaction_data, true);

    // Sort the array by satisfactionVote in ascending order
    usort($array_satisfaction, function($a, $b) {
      return $a['satisfactionVote'] <=> $b['satisfactionVote'];
    });
    ?>

    <table>
      <thead>
      <tr>
        <th>Nom</th>
        <th>Satisfaction Vote</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($array_satisfaction as $user): ?>
        <tr>
        <td><?php echo htmlspecialchars($user['user']['Nom']); ?></td>
        <td><?php echo htmlspecialchars($user['satisfactionVote']); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Le spectateur le plus satisfait</h2>
    <?php
    $notes_moyennes_data = callAPI("/api/usersNotesMoyennes");
    $array_notes_moyennes = json_decode($notes_moyennes_data, true);

    // Sort the array by averageNote in descending order
    usort($array_notes_moyennes, function($a, $b) {
      return $b['noteMoyenne'] <=> $a['noteMoyenne'];
    });
    ?>

    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Note moyenne sur tous les films notés</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($array_notes_moyennes as $user): ?>
          <tr>
            <td><?php echo htmlspecialchars($user['user']['Nom']); ?></td>
            <td><?php echo htmlspecialchars($user['noteMoyenne']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</body>
</html>
