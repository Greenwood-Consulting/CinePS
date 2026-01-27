<?php
echo "<a href='/index.php'><button type='button' class='btn btn-warning'>Accueil</button></a>";
echo "<a href='/historique_film.php'><button type='button' class='btn btn-warning'>Historique</button></a>";
echo "<a href='/statistiques.php'><button type='button' class='btn btn-warning'>Statistiques</button></a>";
if (isset($_SESSION['user']) && $_SESSION['user'] == 1 ){
    echo "<a href='/admin.php'><button type='button' class='btn btn-warning'>Admin</button></a>";
}
?>
