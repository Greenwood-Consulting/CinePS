<a href="<?= base_url('index.php') ?>"><button type="button" class="btn btn-warning">Accueil</button></a>
<a href="<?= base_url('historique_film.php') ?>"><button type="button" class="btn btn-warning">Historique</button></a>
<a href="<?= base_url('statistiques.php') ?>"><button type="button" class="btn btn-warning">Statistiques</button></a>
<?php 
// Si l'utilisateur a le rôle Admin
// TODO: a reprendre lorsque une gestion de rôle sera implementée
if (isset($_SESSION['user']) && $_SESSION['user'] == 1 ): ?>
    <a href="<?= base_url('admin.php') ?>"><button type="button" class="btn btn-warning">Admin</button></a>
<?php endif; ?>
