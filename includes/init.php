<?php
session_start();
require_once(__DIR__ . '/../config/env.php'); // constantes de l'application
require_once(__DIR__ . '/../includes/call_api.php');

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


// Si on vient du formulaire de d'authentification
if(($_POST['form_name'] ?? '') === 'login'){

  // si les credentials sont présents
  if(isset($_POST['user']) && isset($_POST['password'])){
    $body = json_encode([
          'email' => $_POST['user'],
          'password' => $_POST['password']
      ]);
  
    // verifie les credentials de l'utilisateur
    $response = call_API('/api/membre_login_check', 'POST', $body);

    //Le mot de passe correspond
    if(is_object($response) && !isset($response->error)){
      
        // enregistre l'id de l'utilisateur dans la  session
        $_SESSION['user'] = $response->membre_id;     
    }
  }
}

?>