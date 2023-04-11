<?php
//Récupération du token
$body = [
    'username'=>'a@a.fr',
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


//function filmProposes
$curl = curl_init("http://localhost:8000/filmsProposes/14");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: bearer '. $token,
    'Content-Type: application/json'
]);
$films_semaine = curl_exec($curl);
curl_close($curl);

$films_semaine_array = json_decode($films_semaine);
foreach($films_semaine_array as $film){
    echo $film->film->titre."<br/>";
}

//function nextProposeurs
$curl = curl_init("http://localhost:8000/nextProposeurs/9");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: bearer '. $token,
    'Content-Type: application/json'
]);
$next_proposeurs = curl_exec($curl);

$next_proposeurs_array = json_decode($next_proposeurs);
foreach($next_proposeurs_array as $next){
    echo $next->proposeur;
    echo $next->jour."<br/>";

}
?>