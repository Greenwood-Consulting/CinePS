<?php
define('API_URL', '***');
define('API_MAIL', '***');
define('API_PASSWORD', '***');

/**
 * BASE_URL sert de préfixe pour générer les URLs absolues de l'application.
 *
 * Si BASE_URL ne contient pas de domaine, elle doit impérativement commencer par "/"
 * afin que l'URL générée soit interprétée comme absolue par le navigateur.
 *
 * Les URLs absolues évitent les interprétations incorrectes par le navigateur
 * (résolution relative, changement de dossier, rewriting, etc.).
 * 
 * Exemples de valeurs valides:
 *   '/'
 *   '/mon-app/'
 *   'http://localhost:8080/'
 *   'https://example.com/mon-app/'
 */
define('BASE_URL', 'http://localhost:8080');

define('VIDEOS_YOUTUBE', ['ID_VIDEO', 'ID_VIDEO', 'ID_VIDEO']);
?>