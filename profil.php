<?php
include('includes/init.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user'];
// @TODO : ne pas utiliser $membres, pour gérer l'authentification, à refactoriser quand on refactorisera l'Authentification
$json_user = array_values(array_filter($membres, fn($m) => $m->id == $user_id))[0] ?? null;

require_once('includes/header.php');

// Vérifier si les informations de l'utilisateur ont été récupérées avec succès
if (empty($json_user)) {
    echo "Erreur: Impossible de récupérer les informations de l'utilisateur.";
    exit();
}

// Afficher les informations de l'utilisateur
?>

    <title>Profil de l'utilisateur</title>
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
    require_once('includes/auth_form.php');
    ?>
  </div>
</div>

<div class="main-content">

    <h1 class = 'titre'>Profil de l'utilisateur</h1>
    <p>
        <strong>Nom:</strong> <?php echo htmlspecialchars($json_user->nom); ?><br/>
        <strong>Email:</strong> <?php echo htmlspecialchars($json_user->mail); ?>
    </p>
    <br />

    <?php
    // Récupérer les films gagnants
    $json_films_gagnants = call_API("/api/filmsGagnants", "GET");

    // Vérifier si les informations des films ont été récupérées avec succès
    if (empty($json_films_gagnants)) {
        echo "Erreur: Impossible de récupérer les informations des films gagnants.";
        exit();
    }
    ?>

    <?php
    // Filtrer les films gagnants pour ne garder que ceux proposés par l'utilisateur connecté
    $mes_films_gagnants = array_filter($json_films_gagnants, function($film) use ($json_user) {
        // TODO: on suppose ici que la premiere proposition a laquelle est associée un film est celle qui a gagnée
        return $film->propositions[0]->semaine->proposeur->nom === $json_user->nom;
    });
    ?>

    <h2>Les films que j'ai fait découvrir à la PS</h2>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Semaine</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mes_films_gagnants as $film): ?>
                <tr>
                    <td><?php echo htmlspecialchars($film->titre); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($film->propositions[0]->semaine->jour))); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Noter les films vus en PS</h2>
    
    <p class = "explication">
      <u>Explications sur la notation :</u><br>
        <ul class = "explication">
            <li>On ne voit la note moyenne d'un film que si on a soi même noté le film</li>
            <li>Si on reste sur <em>-- Choisir une note --</em> alors cela correspond à ne pas noter le film</li>
            <li>Si on clique sur <em>Ne pas noter</em> c'est équivalent à noter le film mais sans donner de note, pas exemple si vous n'avez pas vu le film. Dans ce cas vous verrez la note moyenne du film</li>
        </ul>
    </p>

    <form method="post" class="form-noter-tous-films" action="save_note.php">
        <button type="submit">Noter tous les films</button>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Semaine</th>
                    <th>Proposeur</th>
                    <th>Ma note</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($json_films_gagnants as $film): ?>
                    <tr>
                        <td><a href="<?php echo htmlspecialchars($film->imdb); ?>" target="_blank"><?php echo htmlspecialchars($film->titre); ?></a></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($film->propositions[0]->semaine->jour))); ?></td>
                        <td><?php echo htmlspecialchars($film->propositions[0]->semaine->proposeur->nom); ?></td>
                        <td>
                            <?php 
                            // get the user rating for this movie
                            $current_user_a_note = false;
                            $current_user_note = null;
                            foreach ($film->notes as $note) {
                                if ($note->membre->id == $_SESSION['user']) {
                                    $current_user_a_note = true;
                                    $current_user_note = $note->note;
                                    break;
                                }
                            }
                            ?>
                            <?php if (!$current_user_a_note): ?>
                                <select name="notes[<?php echo $film->id; ?>]" id="<?php echo $film->id; ?>">
                                    <option value="no">-- Choisir une note --</option>
                                    <option value="0">0 - Christophe Barbier</option>';
                                    <option value="1">1 - Purge</option>';
                                    <option value="2">2 - A chier liquide par terre</option>';
                                    <option value="3">3 - Nul</option>';
                                    <option value="4">4 - Bof</option>';
                                    <option value="5">5 - Ca passe</option>';
                                    <option value="6">6 - Moyen</option>';
                                    <option value="7">7 - Bon</option>';
                                    <option value="8">8 - Très bon</option>';
                                    <option value="9">9 - Borderline Chef d\'oeuvre</option>';
                                    <option value="10">10 - Chef d\'oeuvre</option>';
                                    <option value="11">11 - Up to eleven</option>';
                                    <option value="abs">Ne pas noter</option>
                                </select>
                            <?php else: ?>
                                <?= (is_null($current_user_note)) ? "abstention" : $current_user_note; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= ($film->moyenne === null) ? "non noté" : htmlspecialchars(rtrim(rtrim(number_format($film->moyenne, 2), '0'), '.')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit">Noter tous les films</button>
    </form>

</div>

<?php require_once('includes/footer.php'); ?>
</body>
</html>