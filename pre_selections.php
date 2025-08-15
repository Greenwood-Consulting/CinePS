<?php
require_once('includes/init.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
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

  header('Location: pre_selections.php');
  exit;
}

// ❌ Supprimer une pré-sélection
if (isset($_POST['delete_preselection'])) {
  $id = $_POST['delete_preselection'];
  call_API('/api/preselections/' . $id, 'DELETE');

  header('Location: pre_selections.php');
  exit;
}

// ➕ Creer un nouveau film pour une pré-sélection de l'user
if (isset($_POST['create_film']) && is_numeric($_POST['create_film'])) {
  $body = json_encode([
    'pre_selection_id' => (int) $_POST['create_film'],
    'titre' => $_POST['titre'],
    'date' => '2025-07-07T20:30:00-10:00', // a virer
    'sortie_film' => (int) $_POST['annee'],
    'imdb' => $_POST['imdb']
  ]);
  call_API('/api/films', 'POST', $body);

  header('Location: pre_selections.php');
  exit;
}

// ❌ Supprimer un film
if (isset($_POST['delete_film'])) {
  $id = $_POST['delete_film'];
  call_API('/api/films/' . $id, 'DELETE');

  header('Location: pre_selections.php');
  exit;
}

// ------------- fin reactions au formulaires ----------------------------

// Recupère les pré-sélections de l'user
$preselections = call_API('/api/preselections/membres/' . $user_id, 'GET');

// les pré sélections les plus récentes en premier
$preselections = array_reverse($preselections);

include('header.php'); ?>
<title>Pré-Sélections</title>
<link rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet" href="assets/css/pages/preselections.css">
</head>

<body>

  <header class="header">
    <div class="header__container">
      <h1 class="header__title">
        <img src="assets/logo/logo.png" alt="logo CinePS" />
        CinePS
      </h1>
      <nav class="nav">
        <?php include('nav.php'); ?>
      </nav>
      <?php include('auth_form.php'); ?>
    </div>
  </header>

  <main class="main">
    <h2 class="page__title"><span class="bg-shadow">Pré-Sélections</span></h2>

    <form action="/pre_selections.php" method="POST">
      <div class="preselections__add lt__inline bg-shadow">
        <input type="text" placeholder="thème" name="theme">
        <button class="btn" type="submit" name="create_preselection">Créer une nouvelle liste</button>
      </div>
    </form>

    <ul>
      <?php foreach ($preselections as $preselection): ?>
        <li class="preselection bg-shadow">
          <form action="/pre_selections.php" method="POST">
            <h3 class="preselection__theme lt__inline hover_target">
              <span class="font__dymo"><?= htmlspecialchars($preselection->theme) ?></span>
              <button class="btn btn__light show_on_hover" type="submit" name="delete_preselection" value="<?= htmlspecialchars($preselection->id) ?>" onclick="return confirm('Confirmez-vous la suppression de cette liste ?')">❌</button>
            </h3>
            <ul>
              <?php foreach ($preselection->films as $film): ?>
                <li>
                  <span class="lt__in-line hover_target">
                    <a href="<?= htmlspecialchars($film->imdb) ?>"><?= htmlspecialchars($film->titre) ?>
                      (<?= htmlspecialchars($film->sortie_film) ?>)
                    </a>
                    <button class="btn btn__light show_on_hover" type="submit" name="delete_film" value="<?= htmlspecialchars($film->id) ?>">❌</button>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="lt__inline">
              <input class="preselection__add-titre" type="text" placeholder="titre" name="titre" />
              <input class="preselection__add-imdb" type="text" placeholder="https://www.imdb.com" name="imdb" />
              <input class="preselection__add-annee" type="number" placeholder="année" name="annee" />
              <button class="btn" type="submit" name="create_film" value="<?= htmlspecialchars($preselection->id) ?>">Ajouter un film</button>
            </div>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>

  <?php include('footer.php'); ?>

</body>

</html>