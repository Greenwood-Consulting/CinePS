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

    <p>
        <strong>Version du client CinePS :</strong> <?php echo "VERSION" ?><br/>
        <strong>Version de l'API CinePS :</strong> <?php echo "VERSION" ?><br/>
        <strong>Dépôts GitHub :</strong><br/>
        <strong>Vidéos de release :</strong><br/>
    </p>

    <br />


</div>
</body>
</html>