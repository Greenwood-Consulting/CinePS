<?php
include('header.php');
include('call_api.php');
?>

    <title>A Propose de CinePS</title>
    <link rel="stylesheet" href="historique_film.css">
</head>
<body>

<!-- Barre de navigation -->
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

    <h1 class = 'titre'>A Propos de CinePS</h1>

    <div class="github-repositories">
      <h2>GitHub repositories</h2>
      <ul>
        <li><a href="https://github.com/Greenwood-Consulting/CinePS">cinePS front PHP</a></li>
        <li><a href="https://github.com/Greenwood-Consulting/CinePS-API">cinePS api</a></li>
      </ul>
    </div>

    <!--
    <p>
        <strong>Version du client CinePS :</strong> <?php echo "VERSION" ?><br/>
        <strong>Version de l'API CinePS :</strong> <?php echo "VERSION" ?><br/>
        <strong>Dépôts GitHub :</strong><br/>
    </p>-->

    <br />

    <h2>Vidéos de release</h2>
    <div  class = "main-zone">

    <?php
    if (defined('VIDEOS_YOUTUBE') && is_array(VIDEOS_YOUTUBE)) {
      foreach (array_reverse(VIDEOS_YOUTUBE) as $video) {
        echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . htmlspecialchars($video) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><br/><br/>';
      }
    } else {
      echo "Aucune vidéo disponible.";
    }
    ?>
    </div>

</div>
</body>
</html>