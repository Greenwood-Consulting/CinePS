<?php

// source
// https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html


// configuration par defaut des cookies de session
$sessionParams = [
    // Cookie session name
    'name' => SESSION_COOKIE_NAME,
    // lifetime	0	Durée de vie dans le navigateur = session uniquement (le cookie disparaît à la fermeture du navigateur).
    'cookie_lifetime' => 0,
    // secure	true	Le cookie sera envoyé uniquement via HTTPS. (protection MITM)
    // concernant locahost, le cookie sera toujours envoyé. donc pas de problème pour le dev
    'cookie_secure' => true, // 
    // httponly	true	Le cookie n’est pas accessible en JavaScript → protection contre le vol en cas de faille XSS.
    'cookie_httponly' => true,
    // samesite	'Lax'	Le cookie n’est pas envoyé lors de requêtes cross-site type POST, mais autorisé sur les GET.
    'cookie_samesite' => 'Lax'
];


// si l'utilisateur se logout
if (isset($_POST['logout'])) {
    // supprime la session
    logoutSession();

    // redirection (pattern POST / Redirect / GET)
    // true => si un header Location existe déjà, remplace-le
    // 303 => code HTTP See Other, indique au navigateur "ignore la méthode précédente et fais un GET"
    header('Location: ' . base_url('index.php'), true, 303);
    exit;
}

// Démarre une nouvelle session ou reprend une session existante => peuplement de $_SESSION. 
session_start($sessionParams);

// si la session est invalide
if (!isSessionValid()) {
    // detruit la session et re-crée une session vierge
    renewSession();
}



// ------------------------- helpers -------------------------


/**
 * une session est invalide:
 * si le navigateur de l'utilisateur a changée depuis l'initialisation de la session
 * si la session depasse une certaine durée de vie (SESSION_MAX_AGE)
 * si (bien que la session ne soit pas périmée) l'utilisateur ne s'est pas servi de la session depuis un certain temps (SESSION_TIMEOUT)
 */
function isSessionValid() {
    // Verifie le User agent
    $userAgent = $_SESSION['ua'] ?? "";
    if ($userAgent !== $_SERVER['HTTP_USER_AGENT']) return false;

    $now = time();

    // Vérifie la durée maximale totale
    $creationTimestamp = $_SESSION['created_at'] ?? 0;
    if ($now - $creationTimestamp > SESSION_MAX_AGE) return false;

    // Vérifie la limite d'inactivité
    $lastActivityTimestamp = $_SESSION['last_activity'] ?? 0;
    if ($now - $lastActivityTimestamp > SESSION_TIMEOUT) return false;

    // Mise à jour du timestamp d'activité
    $_SESSION['last_activity'] = $now;

    return true;
}


/**
 * Detruit la session en cours et en créé une autre session neuve et vide
 */
function renewSession() {
    // supprime les variables de la session
    session_unset();
    
    // regenère un nouvel id de session avec les anciennes variables et renvoie un nouveau cookie
    // true => detruit l'ancienne session,
    session_regenerate_id(true);

    $now = time();

    // Peuple $_SESSION avec des détails de connexion
    $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['created_at'] = $now;
    $_SESSION['last_activity'] = $now;
}


/**
 * Detruit la session coté serveur et le cookie de session dans le navigateur
 */
function logoutSession(): void {

    global $sessionParams;

    // Démarre une session existante => peuplement de $_SESSION. 
    session_start($sessionParams);

    // vider les variables de session en RAM serveur
    $_SESSION = [];

    // force l'envoi d'un header Set-Cookie dans la reponse
    // pour indiquer au navigateur de detruire le cookie (date d'exiration il y a ~11h)
    setcookie(
        session_name(),
        '',
        time() - 42000
    );

    // supprime le fichier de session coté serveur
    session_destroy();
}
