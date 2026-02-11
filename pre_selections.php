<?php
require_once(__DIR__ . '/includes/init.php');
require_once(__DIR__ . '/includes/calcul_etat.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
  header('Location: ' . base_url('index.php'));
  exit;
}
$user_id = $_SESSION['user'];

// ------------- reactions au formulaires ----------------------------

// ➕ Creer une nouvelle pré-sélection pour l'user
if (isset($_POST['create_preselection'])) {
  $body = json_encode([
    'membre_id' => $user_id,
    'theme' => $_POST['theme']
  ]);
  call_API('/api/preselections', 'POST', $body);

  header('Location: ' . base_url('pre_selections.php'));
  exit;
}

// ❌ Supprimer une pré-sélection
if (isset($_POST['delete_preselection'])) {
  $id = $_POST['delete_preselection'];
  call_API('/api/preselections/' . $id, 'DELETE');

  header('Location: ' . base_url('pre_selections.php'));
  exit;
}

// ➕ Creer un nouveau film pour une pré-sélection de l'user
// preselection_id doit être un entier et interdire la valeur par défaut ""
if (isset($_POST['create_film']) && isset($_POST['preselection_id']) && ctype_digit($_POST['preselection_id'])) {
  $body = json_encode([
    'pre_selection_id' => (int) $_POST['preselection_id'],
    'titre' => $_POST['titre'],
    'sortie_film' => (int) $_POST['annee'],
    'imdb' => $_POST['imdb']
  ]);
  call_API('/api/films', 'POST', $body);
  
  header('Location: ' . base_url('pre_selections.php'));
  exit;
}

// ❌ Supprimer un film
if (isset($_POST['delete_film'])) {
  $id = $_POST['delete_film'];
  call_API('/api/films/' . $id, 'DELETE');

  header('Location: ' . base_url('pre_selections.php'));
  exit;
}

// 💍 Proposer une pré-sélection
if (isset($_POST['propose_preselection']) && ctype_digit($_POST['propose_preselection'])) {
  $body = json_encode([
    'preselection_id' => (int) $_POST['propose_preselection'],
  ]);
  call_API('/api/propositions', 'POST', $body);

  header('Location: ' . base_url('pre_selections.php'));
  exit;
}

// ------------- fin reactions au formulaires ----------------------------

// Recupère les pré-sélections de l'user
$preselections = call_API('/api/preselections/membres/' . $user_id, 'GET');

// les pré sélections les plus récentes en premier
$preselections = array_reverse($preselections);

// TODO: a placer en fichier de conf? a aligner avec le backend?
$MAX_FILMS_PER_PROPOSITION = 10;

// verifie si une pré-sélection possède un nombre valide de films pour une proposition
function checkPreselectionSize ($preselection) {
  global $MAX_FILMS_PER_PROPOSITION;
  $size = sizeOf($preselection?->films ?? []);
  return $size >0 && $size <= $MAX_FILMS_PER_PROPOSITION;
}

require_once(__DIR__ . '/includes/header.php'); ?>
<title>Pré-Sélections</title>
<link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/preselections.css') ?>">
</head>

<body>

  <header class="header">
    <div class="header__container">
      <h1 class="header__title">
        <img src="<?= base_url('assets/logo/logo.png') ?>" alt="logo CinePS" />
        CinePS
      </h1>
      <nav class="nav">
        <?php require_once(__DIR__ . '/includes/nav.php'); ?>
      </nav>
      <?php require_once(__DIR__ . '/includes/auth_form.php'); ?>
    </div>
  </header>

  <main class="main">
    <h2 class="page__title"><span class="bg-shadow">Pré-Sélections</span></h2>

    <form action="<?= base_url('pre_selections.php') ?>" method="POST">
      <div class="preselections__add lt__inline bg-shadow">
        <input type="text" placeholder="thème" name="theme">
        <button class="btn" type="submit" name="create_preselection">Créer une nouvelle liste</button>
      </div>
    </form>

    <ul>
      <?php foreach ($preselections as $preselection): ?>
        <li class="preselection bg-shadow">
          <h3 class="preselection__theme lt__inline hover_target">
            <form action="<?= base_url('pre_selections.php') ?>" method="POST">
              <span class="font__dymo"><?= htmlspecialchars($preselection->theme) ?></span>

              <?php if($is_proposeur): ?>

                <?php if($proposition_semaine): ?>
                  <span title="Tu as déjà terminé tes propositions cette semaine">⏱️</span>
                <?php else: ?>

                  <?php if(checkPreselectionSize($preselection)): ?>
                    
                    <?php if($no_propositions): ?>
                      <span>💍 </span>
                    <?php else: ?>
                      <span title="Il y a déjà des films que tu as proposé sur la page d'accueil, les listes seront fusionnées">⚠️ </span>
                    <?php endif; ?>

                    <button class="btn show_on_hover" type="submit" name="propose_preselection" value="<?= htmlspecialchars($preselection->id) ?>">Proposer</button>

                  <?php else: ?>
                    <span title="Une pré-sélection doit avoir entre 1 et <?= $MAX_FILMS_PER_PROPOSITION ?> films pour pouvoir être proposée">⛔</span>
                  <?php endif; ?>

                <?php endif; ?>

              <?php endif; ?>

              <button class="btn btn__light show_on_hover" type="submit" name="delete_preselection" value="<?= htmlspecialchars($preselection->id) ?>" onclick="return confirm('Confirmez-vous la suppression de cette liste ? La suppression entraine la suppresion de tous les films de la liste')">❌</button>
            </form>    
          </h3>
          <ul>
            <?php foreach ($preselection->films as $film): ?>
              <li>
                <form action="<?= base_url('pre_selections.php') ?>" method="POST">
                  <span class="lt__in-line hover_target">
                    <a href="<?= htmlspecialchars($film->imdb) ?>"><?= htmlspecialchars($film->titre) ?>
                      (<?= htmlspecialchars($film->sortie_film) ?>)
                    </a>

                    <span class="show_on_hover">

                      <input type="hidden" name="titre" value="<?= htmlspecialchars($film->titre) ?>" />
                      <input type="hidden" name="annee" value="<?= htmlspecialchars($film->sortie_film) ?>" />
                      <input type="hidden" name="imdb" value="<?= htmlspecialchars($film->imdb) ?>" />
  
                      <button type="submit" name="create_film" value="1">dupliquer →&nbsp;</button>

                      <select name="preselection_id">
                        <option value="" disabled selected>-- choisir une pré-sélection --</option>
                        <?php foreach ($preselections as $p): ?>
                          <option value="<?= $p->id ?>"><?= $p->theme ?></option>
                        <?php endforeach; ?>
                      </select>
                        
                      <button class="btn btn__light" type="submit" name="delete_film" value="<?= htmlspecialchars($film->id) ?>">❌</button>

                    </span>
                  </span>
                </form>
              </li>
            <?php endforeach; ?>
          </ul>
          <form action="<?= base_url('pre_selections.php') ?>" method="POST">
            <div class="lt__inline">
              <input class="preselection__add-titre" type="text" placeholder="titre" name="titre" />
              <input class="preselection__add-imdb" type="text" placeholder="https://www.imdb.com" name="imdb" />
              <input class="preselection__add-annee" type="number" placeholder="année" name="annee" />
              <input type="hidden" name="preselection_id" value="<?= htmlspecialchars($preselection->id) ?>" />
              <button class="btn" type="submit" name="create_film" value="1">Ajouter un film</button>
            </div>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>

  <?php require_once(__DIR__ . '/includes/footer.php'); ?>

</body>

</html>