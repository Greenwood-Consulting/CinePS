<?php
session_start();
include 'config/env.php'; // constantes de l'application
include "call_api.php";

$json_current_semaine = call_API("/api/currentSemaine", "GET");
if ($json_current_semaine === null || isset($json_current_semaine->error)) {
  $id_current_semaine = null;
} else {
  $id_current_semaine = $json_current_semaine[0]->id;
}
?>