<?php
session_start();
session_destroy();
// Redirige vers la page précédente si connue, sinon vers une page par défaut
$redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/index.php';
header("Location: $redirectUrl");
exit();
?>