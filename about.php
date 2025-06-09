<?php
include('includes/init.php');
include('header.php');
?>

    <title>A Propos de CinePS</title>
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
      <h2>Dépôts GitHub</h2>
      <ul>
        <li><a href="https://github.com/Greenwood-Consulting/CinePS">CinePS (Front PHP)</a></li>
        <li><a href="https://github.com/Greenwood-Consulting/CinePS-API">CinePS-API</a></li>
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
    <div  class = "release-videos">
    <?php if (defined('VIDEOS_YOUTUBE') && is_array(VIDEOS_YOUTUBE)): ?>
      <?php foreach (array_reverse(VIDEOS_YOUTUBE) as $video): ?>
        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($video) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      <?php endforeach; ?>
    <?php else: ?>
      Aucune vidéo disponible.
    <?php endif; ?>
    </div>

</div>

<?php include('footer.php'); ?>
</body>
</html>