<?php 
$apiKey = getenv('OPENAI_API_KEY');
$ghKey = getenv('GITHUB_TOKEN');

function call_API_POST_ChatGPT($json_body, $apiKey){
    $curl = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Authorization: bearer '. $apiKey,
      'Content-Type: application/json',
      'Content-Length: ' . strlen($json_body)
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_body);
    $api_response = curl_exec($curl);
    $decoded_response = json_decode($api_response);
    curl_close($curl);
    return $api_response;
}

// Authentification à Github CLI
shell_exec("echo ".$ghKey." | gh auth login --with-token");

// Récupération du git log de la version qui correspond au dernier tag
$command = 'git log v2.2..v2.3'; // Commande Git à exécuter
$git_log = shell_exec($command);


if ($git_log === null) {
    echo "Erreur lors de l'exécution de la commande.";
    exit();
} else { // call API OpenAI
    $prompt = "Je te fournis à la fin de ce prompt un extract de gitlog en input et voici les instructions que tu dois suivre :
- La sortie doit être en français.
- Le format de la sortie doit être du Markdown (.md) afin que je copie/colle la release note générée dans Github. Donne moi une sortie comme une citation de code au format Markdown.
- Ne mets pas de balises Markdown autour de la sortie.
- Ne mets pas de liens vers les commits. Seulement les changements en français.
- il faut que tu mentionnes les nouvelles features, les évolutions
de features visibles par les utilisateurs, les bugs corrigés, 
les optimisations (mais sans rentrer dans les détails techniques)
et les évolutions techniques importantes
- Les optimisation doivent être incluses dans 'Evolutions techniques'
- Ne mentionne pas les commits de merge
- Ne mentionne pas les choses qui sont des petits détails techniques
- Ne mentionne pas les modifications du modèle de données
- Traite les commits par ordre du plus ancien au plus récent
- Les suppressions de code obsolète ou nettoyages de commentaires ne 
doivent pas être mentionnés.
Voici maintenant le texte du git log sur lequel tu dois travailler : ".$git_log;

    $body = [
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7
    ];
    $json_body = json_encode($body);
    $api_response = call_API_POST_ChatGPT($json_body, $apiKey);

    $json_response = json_decode($api_response);

    $release_note =  $json_response->choices[0]->message->content;

    // Créer un fichier temporaire
    $filePath = "RELEASE_NOTES.md";
    file_put_contents($filePath, $release_note);

    // Créer une release sur Github avec la CLI Github
    $response_create_release = shell_exec("gh release create v2.7 --title \"Release v2.7\" --notes-file $filePath --draft");
}












