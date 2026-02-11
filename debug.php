<?php
require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/calcul_etat.php');
require_once(__DIR__ . '/includes/header.php');
?>

<title>CinePs Debug</title>
<style>
  ul li.true:before {
    content: '✔ ';
    color: green;
  }

  ul li.false:before {
    content: '✘ ';
    color: red;
  }

  .truncate {
    white-space: nowrap;
    /* Empêche le retour à la ligne */
    overflow: hidden;
    /* Coupe tout ce qui dépasse */
    text-overflow: ellipsis;
    /* Remplace la fin par "..." */
    display: inline-block;
    /* fix ellipsis height */
    line-height: 1;
  }
</style>
</head>

<body>


  <h1>CinePs Debug</h1>

  <article>
    <h3>Pages</h3>
    <ul>
      <?php
      $pages = array(
        "/",
        "/index.php",
        "/admin.php",
        "/historique_film.php",
        "/inscription.php",
        "/pre_selections.php",
        "/profil.php",
        "/resultat_vote.php",
        "/save_vote.php",
        "/statistiques.php"
      );
      ?>

      <?php foreach ($pages as $page): ?>
        <li><a href="<?= base_url($page) ?>"><?= $page ?></a></li>
      <?php endforeach; ?>
    </ul>
  </article>

  <article>
    <h3>Constantes d'initialisation requises</h3>
    <ul>
      <li>[token JWT API] <code>$_SESSION['token']</code> : <span class="truncate" style="width: 300px;"><?= isset($_SESSION['token']) ? $_SESSION['token'] : "aucun" ?></span></li>
      <li>[member list] <code>sizeof($membres)</code> : <?= sizeof($membres) ?></li>
    </ul>
  </article>

  <article>
    <h3>Variables d'etat</h3>
    <ul>
      <li>User
        <ul>
          <li><code>$_SESSION['user']</code> (membre_id): <?= isset($_SESSION['user']) ? $_SESSION['user'] : "aucun" ?></li>
          <li class="<?= isset($json_membre) ? "true" : "false" ?>"><code>$json_membre</code> : <?= isset($json_membre) ? json_encode($json_membre) : "aucun" ?></li>
          <li class="<?= $is_actif ? "true" : "false" ?>"><code>$is_actif</code> : user actif? (guest semble actif par defaut)</li>
          <li class="<?= $connecte ? "true" : "false" ?>"><code>$connecte</code> : l'utilisateur est-il connecté?</li>
          <li class="<?= $is_proposeur ? "true" : "false" ?>"><code>$is_proposeur</code> : l'utilisateur est-il proposeur?</li>
          <li class="<?= $current_user_a_vote ? "true" : "false" ?>"><code>$current_user_a_vote</code> : l'utilisateur a-t-il voté?</li>
        </ul>
      </li>
      <br />
      <li>Flow Semaine
        <ul>
          <li class="<?= isset($json_current_semaine) ? "true" : "false" ?>"><code>$json_current_semaine</code></li>
          <li class="<?= $id_current_semaine ? "true" : "false" ?>"><code>$id_current_semaine</code> : <?= isset($json_current_semaine) ? $id_current_semaine : "none" ?></li>
          <li class="<?= $proposeur_cette_semaine ? "true" : "false" ?>"><code>$proposeur_cette_semaine</code> : un proposeur est-il défini cette semaine? <?= json_encode($json_current_semaine->proposeur) ?></li>
          <li class="<?= $no_propositions ? "true" : "false" ?>"><code>$no_propositions</code> : <u>aucune</u> proposition n'a été faite cette semaine?</li>
          <li class="<?= $proposition_semaine ? "true" : "false" ?>"><code>$proposition_semaine</code> : les propositions ont elles été faites (<u>toutes</u> les propositions ont été validées par le proposeur) ?</li>
          <li class="<?= $etat_theme_non_propose ? "true" : "false" ?>"><code>$etat_theme_non_propose</code> : le thème n'est il PAS proposé?</li>
          <li class="<?= $vote_termine_cette_semaine ? "true" : "false" ?>"><code>$vote_termine_cette_semaine</code> : le vote est il terminé? (tous les users actif ont votés, ou la deadline est dépassée)</li>
          <li class="<?= isset($vote_deadline) ? "true" : "false" ?>"><code>$vote_deadline</code> : <?= isset($vote_deadline) ? $vote_deadline : "none" ?></li>
          <li class="<?= isset($dLink) ? "true" : "false" ?>"><code>$dLink</code> - lien de telechargement: <?= isset($dLink) ? $dLink : "none" ?></li>
        </ul>
      </li>
    </ul>
  </article>

</body>

</html>