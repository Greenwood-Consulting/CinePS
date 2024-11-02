<?php
session_start();

include "call_api.php";

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
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>
<body>
    <h1>Profil de l'utilisateur</h1>
    <p><strong>Nom:</strong> <?php echo htmlspecialchars($array_user->Nom); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($array_user->mail); ?></p>
    <!-- Ajoutez d'autres informations de l'utilisateur ici -->
    <a href="deconnexion.php"><button type="button" class="btn btn-warning">Se déconnecter</button></a>

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
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Semaine</th>
                <th>Proposeur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($array_films as $film): ?>
                <tr>
                    <td><?php echo htmlspecialchars($film->titre); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($film->propositions[0]->semaine->jour))); ?></td>
                    <td><?php echo htmlspecialchars($film->propositions[0]->semaine->proposeur->Nom); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>