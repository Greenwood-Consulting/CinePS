<?php
// Récupérer un tolen d'accàs l'API
function recupererToken(){
    $body = [
      'email'=>API_MAIL,
      'password'=>API_PASSWORD
    ];
    $json_body = json_encode($body);

    $curl = curl_init(API_URL."/api/login_check");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_body);
    $response = curl_exec($curl);
    curl_close($curl);
    $response_array = json_decode($response);
    $token = $response_array->token;

    return $token;
}

if (! isset($_SESSION['token']) || empty($_SESSION['token'])){
  $_SESSION['token'] = recupererToken();
}

function call_API($entry_point, $verbe, $body = null, $result_as_array = false, $retry = 1){
  $curl = curl_init(API_URL.$entry_point);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  // Paramétrage des headers
  $headers = [
    'Authorization: bearer '. $_SESSION['token'],
    'Content-Type: application/json'
  ];
  if ($verbe == 'POST' || $verbe == 'PATCH') {
    $headers[] = 'Content-Length: ' . strlen($body);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
  }
  if ($verbe == 'PATCH') {
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
  }
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  // exécution de la requête
  $api_response = curl_exec($curl);

  // Si l'API n'est pas disponible, on sort de la fonction
  if ($api_response === false) {
    // L'API n'est pas disponible
    http_response_code(503);
    echo "Erreur 503: Service Unavailable. L'API n'est pas accessible.";
    exit();
  }

  if ($result_as_array) {
    $decoded_response = json_decode($api_response, true);
  } else {
    $decoded_response = json_decode($api_response);
  }
  
  // Si le token est expiré, on génère un nouveau token
  //@TODO: pourquoi ne pas permettre de refaire la requet dans le cas $result_as_array = true ?
  if (is_object($decoded_response) && isset($decoded_response->code) && $decoded_response->code == "401") {
    if($retry > 0) {
      $_SESSION['token'] = recupererToken();
      $decoded_response = call_API($entry_point, $verbe, $body, $result_as_array, $retry - 1);
    } else {
      http_response_code(401);
      echo "Erreur 401: Unauthorized.";
      exit;
    }
  }

  //@TODO: manage other error codes

  curl_close($curl);

  return $decoded_response;
}

?>