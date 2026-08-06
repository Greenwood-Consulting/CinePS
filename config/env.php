<?php

/**
 * Lit une variable d'environnement requise.
 *
 * @throws RuntimeException si la variable est absente ou vide.
 */
function cineps_required_env(string $name): string
{
    $value = getenv($name);

    if ($value === false || trim($value) === '') {
        throw new RuntimeException(sprintf(
            'La variable d\'environnement requise "%s" est absente.',
            $name
        ));
    }

    return $value;
}

/**
 * Lit une variable d'environnement optionnelle.
 */
function cineps_env(string $name, string $default = ''): string
{
    $value = getenv($name);

    return ($value === false || $value === '') ? $default : $value;
}

define('API_URL', rtrim(cineps_required_env('API_URL'), '/'));
define('API_MAIL', cineps_required_env('API_MAIL'));
define('API_PASSWORD', cineps_required_env('API_PASSWORD'));

// Exemple : https://cineps.example.com ou /cineps/
define('BASE_URL', cineps_env('BASE_URL', '/'));

// Liste séparée par des virgules : VIDEO_ID_1,VIDEO_ID_2
$videosYoutube = array_values(array_filter(
    array_map('trim', explode(',', cineps_env('VIDEOS_YOUTUBE'))),
    static fn (string $videoId): bool => $videoId !== ''
));
define('VIDEOS_YOUTUBE', $videosYoutube);
