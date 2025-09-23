<?php
include('includes/init.php');
include('common.php');
require_once('includes/calcul_etat.php');

function renomme_et_permute_films(array $propositions): array {

  usort($propositions, function ($a, $b) {
      // Création d'un nombre pseudo-aléatoire à partir de l'ID
      $hashA = crc32($a->film->titre);
      $hashB = crc32($b->film->titre);
      return $hashA <=> $hashB;
  });

  // Renommer les films avec des noms de chevaux
  $noms_chevaux = ['Prince du Vent', 'Velours Tempétueux', 'Général du Pommeau', 'Cacahuète du logis', 'Jolly-Jumper', 'Bricks and Mortar', 'Brise de Nuit', 'Prince de la Vigne'];
  foreach ($propositions as $index => &$proposition) {
      $proposition->film->titre = $noms_chevaux[$index % count($noms_chevaux)];
  }
  unset($proposition); // rompre la référence avec le dernier élément
  return $propositions;
}

// get propositions pour le classement des films de la semaine
$propositions = $json_current_semaine->propositions;

$obfuscatePropositions = !$vote_termine_cette_semaine && !$current_user_a_vote && !$is_proposeur;

if ($obfuscatePropositions) {
  // Permutation des films afin qu'on ne puisse pas savoir quel film correspond à quel score
  $propositions = renomme_et_permute_films($propositions);
}


//construction du tableau data_proposeur
$data_proposeurs = [];
$json_proposeurs = call_API("/api/getNbPropositionsParProposeur", "GET");
foreach($json_proposeurs as $proposeurs){
  array_push($data_proposeurs, array("Proposeur" => $proposeurs->proposeur, "nombre" => $proposeurs->nb_semaines));
}

$count_data_proposeurs = count($data_proposeurs);

// récupérer les films gaganants
$array_filmsGagnants = call_API("/api/filmsGagnants", "GET"); // @TODO : renommer $array_filmsGagnants en $json_films_gagnants ?

//Construction du tableau data_année
$data_annee = [];
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

include('header.php');
?>


    <link href="historique_film.css" rel="stylesheet">

    <title>Statistiques</title>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

    // Google Charts: "Classic" vs "Material Design"
    // The Material Charts are in beta. The appearance and interactivity are largely final, but many of the options available in Classic Charts are not yet available in them
    // https://developers.google.com/chart/interactive/docs/gallery/barchart#creating-material-bar-charts
    
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {packages: ['corechart', 'bar']});
    
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      const propositions = <?= json_encode($propositions) ?>;

      // draw data_score: Classement des films de la semaine
      const data_table = new google.visualization.DataTable();
      data_table.addColumn('string', 'Film');
      data_table.addColumn('number', '');
      data_table.addRows(propositions.map(p => [p.film.titre, p.score]));

      var options = {
        chart: {
          title: ''
        },
        chartArea: {
          left: 200
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
      
      // https://developers.google.com/chart/interactive/docs/gallery/barchart
      const ranking_chart = new google.visualization.BarChart(document.getElementById('classement-semaine-chart'));
      ranking_chart.draw(data_table, options);

      <?php if (!$obfuscatePropositions): ?>
        // Gestion du clic sur une barre proposition
        google.visualization.events.addListener(ranking_chart, 'select', function() {
          const selection = ranking_chart.getSelection();
          if (selection.length > 0) {
            const row = selection[0].row;
            const propositionClicked = propositions[row];
            // ouverture d'un nouvel onglet vers le lien imdb
            window.open(propositionClicked.film.imdb, '_blank');
          }
        });
      <?php endif; ?>

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
      require_once('includes/auth_form.php');
      ?>
    </div>
  </div>

  <div class="main-content">
    <h1 class="titre">Statistiques</h1>
    <h2>Classement des films de la semaine</h2>
    <div id="classement-semaine-chart"  style="width: 40%; height: 200px" class="main-zone stat-chart"></div>

    <h2> Films vus par décennie</h2>
    <div id="chart_film_année" style="width: 40%; height: 200px" class="main-zone"></div>
    
    <h2> Nombre de fois que les membres ont été proposeurs</h2>
    <div id="piechart" style="width: 40%; height: 500px;" class="main-zone" ></div>

    <h2>Le votant le plus satisfait</h2>

    <p class = "explication">
      <u>Explications  :</u><br>
      Ce classement se base sur l'ordre des films proposés par chaque utilisateur lors de la phase de vote.
      Il mesure à quel point le film sélectionné à chaque PS est cohérent avec le vote de chaque utilisateur.
      <ul>
        <li>Un <strong>score bas</strong> indique que les films choisis sont globalement en adéquation avec les votes de l'utilisateur</li>
        <li>Un <strong>score élevé</strong> indique que les films choisis sont globalement en inadéquation avec les votes de l'utilisateur.</li>
      </ul>
    </p>

    <p class = "explication">
      Concrètement, pour chaque utilisateur, le score est la moyenne des votes sur tous les films <strong>vus en PS</strong>. Les films proposés mais qui n'ont pas été retenus ne sont pas pris en compte.
    </p>

    <?php
    $array_satisfaction = call_API("/api/usersSatisfaction", "GET", null, true);

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
        <td><?php echo htmlspecialchars($user['user']['nom']); ?></td>
        <td><?php echo rtrim(rtrim(number_format($user['satisfactionVote'], 2), '0'), '.'); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Le spectateur le plus satisfait</h2>

        <p class = "explication">
          <u>Explications  :</u><br>
          Ce classement se base sur la note attribuée par chaque utilisateur aux films vus en PS.<br />
          Il mesure à quel point le spectateur a apprécié, en moyenne, les films qu'il a pu visionner.
          Concrètement : il s'agit de la moyenne des notes qu'il a attribuées aux différents films (via sa page de profil ou la page historique).
        </p>
    <?php
    $array_notes_moyennes = call_API("/api/usersNotesMoyennes", "GET", null, true);

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
            <td><?php echo htmlspecialchars($user['user']['nom']); ?></td>
            <td>
              <?php 
              $noteText = $user['nbNotes'] == 1 ? 'note' : 'notes';
              echo rtrim(rtrim(number_format($user['noteMoyenne'], 2), '0'), '.') . "&nbsp; (" . $user['nbNotes'] . " " . $noteText . ")";
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php $nb_films_gagants = count($array_filmsGagnants); ?>
    <h2>Classement des meilleurs films (<?php echo $nb_films_gagants ?> films au total)</h2>

    <?php

    // Sort the films by average score in descending order
    usort($array_filmsGagnants, function($a, $b) {
      return $b->moyenne <=> $a->moyenne;
    });
    // Sort the films by average score in descending order, and by number of notes in descending order if averages are equal
    usort($array_filmsGagnants, function($a, $b) {
      if ($b->moyenne == $a->moyenne) {
      return count($b->notes) <=> count($a->notes);
      }
      return $b->moyenne <=> $a->moyenne;
    });

    // Filter out films that do not have an average score
    $array_filmsGagnants = array_filter($array_filmsGagnants, function($film) {
      return isset($film->moyenne);
    });

    /***************************************************************************************************
     * Find the proposeur(s) with the most high score films
     ***************************************************************************************************/
    // Filter films with an average score of 9 or higher
    $films_high_score = array_filter($array_filmsGagnants, function($film) {
      return isset($film->moyenne) && $film->moyenne >= 9;
    });

    // Count the number of high score films per proposeur
    $proposeur_high_score_count = [];
    foreach ($films_high_score as $film) {
      $proposeur = $film->propositions[0]->semaine->proposeur->nom;
      if (!isset($proposeur_high_score_count[$proposeur])) {
        $proposeur_high_score_count[$proposeur] = 0;
      }
      $proposeur_high_score_count[$proposeur]++;
    }

    // Find the proposeur(s) with the most high score films
    $max_high_score_films = max($proposeur_high_score_count);
    $top_proposeurs = array_keys($proposeur_high_score_count, $max_high_score_films);


    /***************************************************************************************************
     * Find the proposeur(s) with the most low score films
     ***************************************************************************************************/
    // Filter films with an average score strictly less than 5
    $films_low_score = array_filter($array_filmsGagnants, function($film) {
      return isset($film->moyenne) && $film->moyenne < 5;
    });

    // Count the number of low score films per proposeur
    $proposeur_low_score_count = [];
    foreach ($films_low_score as $film) {
      $proposeur = $film->propositions[0]->semaine->proposeur->nom;
      if (!isset($proposeur_low_score_count[$proposeur])) {
      $proposeur_low_score_count[$proposeur] = 0;
      }
      $proposeur_low_score_count[$proposeur]++;
    }

    // Find the proposeur(s) with the most low score films
    $max_low_score_films = max($proposeur_low_score_count);
    $bottom_proposeurs = array_keys($proposeur_low_score_count, $max_low_score_films);

    /***************************************************************************************
     * Affichage des meilleurs pourvoyeurs de chefs d'oeuvre et de purges
     ***************************************************************************************/
    // Chefs d'oeuvre
    if (count($top_proposeurs) > 1) {
      $last_proposeur = array_pop($top_proposeurs);
      echo "<p class = \"explication\">";
      echo implode(', ', $top_proposeurs) . ' et ' . htmlspecialchars($last_proposeur);
      echo " sont les meilleurs pourvoyeurs de chefs d'oeuvre avec " . htmlspecialchars($max_high_score_films) . " films ayant une moyenne de 9 ou plus. 🏆";
      echo "</p>";
    } else {
      echo "<p class = \"explication\">" . htmlspecialchars($top_proposeurs[0]) . " est le meilleur pourvoyeur de chefs d'oeuvre avec " . htmlspecialchars($max_high_score_films) . " films ayant une moyenne de 9 ou plus. 🏆</p>";
    }

    // Purges
    if (count($bottom_proposeurs) > 1) {
      $last_proposeur = array_pop($bottom_proposeurs);
      echo "<p class = \"explication\">";
      echo implode(', ', $bottom_proposeurs) . ' et ' . htmlspecialchars($last_proposeur);
      echo " sont les meilleurs pourvoyeurs de purges avec " . htmlspecialchars($max_low_score_films) . " films ayant une moyenne strictement inférieure à 5. 🤮";
      echo "</p>";
    } else {
      echo "<p class = \"explication\">" . htmlspecialchars($bottom_proposeurs[0]) . " est le meilleur pourvoyeur de purges avec " . htmlspecialchars($max_low_score_films) . " films ayant une moyenne strictement inférieure à 5. 🤮</p>";
    }
    ?>



    <table>
      <thead>
        <tr>
          <th>Titre du film</th>
          <th>Semaine</th>
          <th>Proposeur</th>
          <th>Moyenne</th>
          <th>Nombre de notes</th>
          <th>Écart type</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $total_moyennes_tous_films_gagants = 0;
        $count_films_with_moyenne = 0;
        foreach ($array_filmsGagnants as $film):
          // Moyenne générale de tous les films 
          if ($film->moyenne !== null) {
            $total_moyennes_tous_films_gagants += $film->moyenne;
            $count_films_with_moyenne++;
          }
          $nb_notes = count(array_filter($film->notes, function($note) {
            return $note->note !== null;
          }));
        ?>
          <tr>
            <td><a href="<?php echo htmlspecialchars($film->imdb); ?>" target="_blank"><?php echo htmlspecialchars($film->titre); ?></a></td>
            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($film->propositions[0]->semaine->jour))); ?></td>
            <td><?php echo htmlspecialchars($film->propositions[0]->semaine->proposeur->nom); ?></td>
            <td><?php echo rtrim(rtrim(number_format($film->moyenne, 2), '0'), '.'); ?></td>
            <td><?php echo $nb_notes . ($nb_notes == 1 ? " note" : " notes"); ?></td>
            <td><?php echo is_null($film->ecartType) ? "-" : $film->ecartType . " - " . ($film->ecartType > 2 ? "clivant" : "consensuel") ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php
      $moyenne_generale_films_gagants = $total_moyennes_tous_films_gagants / $count_films_with_moyenne; 
    ?>
    <p class = "explication">
      La moyenne générale des films gagnants est de <?php echo rtrim(rtrim(number_format($moyenne_generale_films_gagants, 2), '0'), '.'); ?>
      <span class="info-icon" title="Moyenne non pondérée de toutes les moyennes des films vus en PS qui ont une moyenne. Cad qu'on fait la moyenne de toutes les moyennes lorsqu'elles existent.">&#9432;</span>
    </p>
    <style>
      .info-icon {
      cursor: pointer;
      border-bottom: 1px dotted #000;
      }
    </style>

    <h2>Le meilleur proposeur</h2>
    
    <p class = "explication">
      <u>Explications  :</u><br>
      Ce classement se base sur les notes attribuées par les spectateurs vis à vis des films d'un proposeur donné.<br>
      Il mesure a quel point les films proposés par le proposeur et visionnés en PS ont été appréciés.<br />
      Concrètement, pour chaque proposeur on calcule la moyenne des notes attribuées par les spectateurs aux films vus en PS issues de ses propositions, en excluant la note du proposeur lui-même.
    </p>
    <?php
    // classer les films par membre
    $moyennes_films_par_proposeur = [];
    foreach ($array_filmsGagnants as $film) {
      $proposeur = $film->propositions[0]->semaine->proposeur;

      // Retirer la note du proposeur si elle existe
      $notes_film_du_proposeur = array_filter($film->notes, function($note) use ($proposeur) {
        // on enlève la note du proposeur et les notes qui sont à null (qui correspondent à des abstentions)
        return $note->membre->id !== $proposeur->id && $note->note !== null;
      });

      $nb_notes_film_du_proposeur = count($notes_film_du_proposeur);
      // calcul de la note moyenne du film, en excluant la note du proposeur
      if ($nb_notes_film_du_proposeur === 0) {
        continue;
      } else {
        $note_moyenne_film_hors_proposeur = array_sum(array_column($notes_film_du_proposeur, 'note')) / $nb_notes_film_du_proposeur;
      }

      // Ajout de la moyenne du film au total des moyennes du proposeur
      if (!isset($moyennes_films_par_proposeur[$proposeur->id])) {
        $moyennes_films_par_proposeur[$proposeur->id] = ['total_moyennes' => 0, 'count' => 0, 'nb_notes' => 0];
        $moyennes_films_par_proposeur[$proposeur->id]['Nom'] = $proposeur->nom;
      }

      $moyennes_films_par_proposeur[$proposeur->id]['total_moyennes'] += $note_moyenne_film_hors_proposeur;
      $moyennes_films_par_proposeur[$proposeur->id]['count'] += 1;
      $moyennes_films_par_proposeur[$proposeur->id]['nb_notes'] += $nb_notes_film_du_proposeur; ;
    }

    // calculer la moyenne des moyennes hors proposeur des films gagnants par prposeur
    $proposeur_moyenne_generale = [];
    foreach ($moyennes_films_par_proposeur as $proposeur_id => $moyennes_films) {
      $proposeur_moyenne_generale[] = [
        'nom_proposeur' => $moyennes_films['Nom'],
        'moyenne_generale' => $moyennes_films['total_moyennes'] / $moyennes_films['count'],
        'nb_notes' => $moyennes_films['nb_notes']
      ];
    }

    // trier les proposeurs par moyenne décroissante
    usort($proposeur_moyenne_generale, function($a, $b) {
      return $b['moyenne_generale'] <=> $a['moyenne_generale'];
    });
  ?>
  <table>
    <thead>
    <tr>
      <th>Proposeur</th>
      <th>Note moyenne des films gagants<br />de ce proposeur</th>
      <th>Nombre de notes reçues<br />par les films du proposeur</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($proposeur_moyenne_generale as $proposeur): ?>
      <tr>
      <td><?php echo htmlspecialchars($proposeur['nom_proposeur']); ?></td>
      <td><?php echo rtrim(rtrim(number_format($proposeur['moyenne_generale'], 2), '0'), '.'); ?></td>
      <td>
        <?php 
          echo $proposeur['nb_notes']; 
          echo $proposeur['nb_notes'] == 1 ? " note" : " notes"; 
        ?>
      </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  </div>
<?php require_once('includes/footer.php'); ?>
</body>
</html>
