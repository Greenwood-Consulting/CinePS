<?php
session_start();

include('call_api.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user'];
$user = callAPI("/api/membres/" . $user_id);
$array_user = json_decode($user);

// Vérifier si les informations de l'utilisateur ont été récupérées avec succès
if (empty($array_user)) {
    echo "Erreur: Impossible de récupérer les informations de l'utilisateur.";
    exit();
}

// Afficher les informations de l'utilisateur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    include('auth_form.php');
    ?>
  </div>
</div>

<div class="main-content">

    <h1 class = 'titre'>Profil de l'utilisateur</h1>
    <p>
        <strong>Nom:</strong> <?php echo htmlspecialchars($array_user->Nom); ?><br/>
        <strong>Email:</strong> <?php echo htmlspecialchars($array_user->mail); ?>
    </p>
    <br />

    <?php
    // Récupérer les films gagnants
    $films_gagnants = callAPI("/api/filmsGagnants");
    $array_films = json_decode($films_gagnants);

    // Vérifier si les informations des films ont été récupérées avec succès
    if (empty($array_films)) {
        echo "Erreur: Impossible de récupérer les informations des films gagnants.";
        exit();
    }
    ?>

    <?php
    // Filtrer les films gagnants pour ne garder que ceux proposés par l'utilisateur connecté
    $mes_films_gagnants = array_filter($array_films, function($film) use ($array_user) {
        return $film->propositions[0]->semaine->proposeur->Nom === $array_user->Nom;
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
    <form method="post" class="form-noter-tous-films" action="save_note.php">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Semaine</th>
                    <th>Proposeur</th>
                    <th>Noter</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($array_films as $film): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($film->titre); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($film->propositions[0]->semaine->jour))); ?></td>
                        <td><?php echo htmlspecialchars($film->propositions[0]->semaine->proposeur->Nom); ?></td>
                        <td>
                            <?php 
                            $current_user_a_note = false;
                            foreach ($film->notes as $note) {
                                if ($note->membre->id == $_SESSION['user']) {
                                    $current_user_a_note = true;
                                    break;
                                }
                            }
                            if (!$current_user_a_note): ?>
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
                                    <option value="abs">S'abstenir</option>
                                </select>
                            <?php 
                                else: 
                                    echo htmlspecialchars($film->moyenne);
                            endif; 
                                ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit">Noter tous les films</button>
    </form>

</div>
</body>
</html>