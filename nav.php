<a href='index.php'><button type='button' class='btn btn-warning'>Accueil</button></a>
<a href='historique_film.php'><button type='button' class='btn btn-warning'>Historique</button></a>
<a href='stat_barre.php'><button type='button' class='btn btn-warning'>Statistiques</button></a>
<?php if (isset($_SESSION['user']) && $_SESSION['user'] == 1): ?>
    <a href='admin.php'><button type='button' class='btn btn-warning'>Admin</button></a>
<?php endif; ?>