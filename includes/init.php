<?php
session_start();
include 'config/env.php'; // constantes de l'application
require_once 'includes/helpers.php';
include "call_api.php";

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