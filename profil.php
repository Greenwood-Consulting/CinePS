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
</body>
</html>