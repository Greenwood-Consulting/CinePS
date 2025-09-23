<?php
session_start();
require_once './config/env.php'; // constantes de l'application
require_once './includes/call_api.php';

// TODO: prend 200ms. Les pages n'en ont pas toutes besoin. A séparer
$json_current_semaine = call_API("/api/currentSemaine", "GET");
if ($json_current_semaine === null || isset($json_current_semaine->error)) {
  $id_current_semaine = null;
} else {
  $id_current_semaine = $json_current_semaine->id;
}

$membres = call_API("/api/membres", "GET");
if(!isset($membres) || !is_array($membres)) {
  echo "app init failed: cannot get members";
  exit;
}

?>