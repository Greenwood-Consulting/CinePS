<?php
// Récupérer un tolen d'accàs l'API
function recupererToken(){
    $body = [
      'email'=>'a@a.fr',
      'password'=>'password'
    ];
    $json_body = json_encode($body);

    $curl = curl_init("http://localhost:8000/api/login_check");
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

function callAPI($entry_point){
    // Paramétrage de la requête
    $curl = curl_init("http://localhost:8000".$entry_point);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Authorization: bearer '. $_SESSION['token'],
      'Content-Type: application/json'
    ]);
    $api_response = curl_exec($curl);
    $decoded_response = json_decode($api_response);
    // Si le token est expiré, on génère un nouveau token
    if (is_object($decoded_response) && isset($decoded_response->code) && $decoded_response->code == "401") {
      $_SESSION['token'] = recupererToken();
      // On refait la requête avec le nouveau token
      curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: bearer '. $_SESSION['token'],
        'Content-Type: application/json'
      ]);
      $api_response = curl_exec($curl);
    }

    curl_close($curl);
    return $api_response;
}

function callAPI_POST($entry_point, $body){
    $curl = curl_init("http://localhost:8000".$entry_point);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Authorization: bearer '. $_SESSION['token'],
      'Content-Type: application/json',
      'Content-Length: ' . strlen($body)
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    $api_response = curl_exec($curl);
    $decoded_response = json_decode($api_response);
    // Si le token est expiré, on génère un nouveau token
    if (is_object($decoded_response) && isset($decoded_response->code) && $decoded_response->code == "401") {
      $_SESSION['token'] = recupererToken();
      // On refait la requête avec le nouveau token
      curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: bearer '. $_SESSION['token'],
        'Content-Type: application/json'
      ]);
      $api_response = curl_exec($curl);
    }
    curl_close($curl);
    return $api_response;
}

function callAPI_PATCH($entry_point, $body){
    $curl = curl_init("http://localhost:8000".$entry_point);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Authorization: bearer '. $_SESSION['token'],
      'Content-Type: application/json',
      'Content-Length: ' . strlen($body)
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    $api_response = curl_exec($curl);
    $decoded_response = json_decode($api_response);
    // Si le token est expiré, on génère un nouveau token
    if (is_object($decoded_response) && isset($decoded_response->code) && $decoded_response->code == "401") {
      $_SESSION['token'] = recupererToken();
      // On refait la requête avec le nouveau token
      curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: bearer '. $_SESSION['token'],
        'Content-Type: application/json'
      ]);
      $api_response = curl_exec($curl);
    }
    curl_close($curl);
    return $api_response;
}

?>