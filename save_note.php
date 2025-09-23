<?php
include('includes/init.php');
require_once('includes/common.php');

// @TODO: simplifier les if

// Sauvegare de plusieurs notes en même temps
if(isset($_POST['notes'])){
    $membre_id = ($_SESSION['user']);

    foreach ($_POST['notes'] as $id_film => $note_film) {
        $note = addslashes($note_film);

        if ($note != "no" && $note != "abs") {
            $array_note = array(
                "film_id" => $id_film,
                "membre_id" => $membre_id,
                "note" => $note
            );
            $json_note = json_encode($array_note);
            call_API("/api/note", "POST", $json_note);
        }
        if ($note == "abs") {
            $array_abstention = array(
                "film_id" => $id_film,
                "membre_id" => $membre_id
            );
            $json_abstention = json_encode($array_abstention);
            call_API("/api/note", "POST", $json_abstention);
        }
    }
    header('Location: profil.php');
}

// Sauvegarde d'une note
if(isset($_POST['id_film'])){
    $membre_id = ($_SESSION['user']);
    $id_film = $_POST['id_film'];
    $note = addslashes(($_POST['note']));

    if ($note == "abs") { // L'utilisateur décide de ne pas noter le film
        $array_abstention = array(
            "film_id" => $id_film,
            "membre_id" => $membre_id
        );
        $json_abstention = json_encode($array_abstention);
        call_API("/api/note", "POST", $json_abstention);
    } else { // L'utilisateur note le film
        $array_note = array(
            "film_id" => $id_film,
            "membre_id" => $membre_id,
            "note" => $note,
        );
        $json_note = json_encode($array_note);
        call_API("/api/note", "POST", $json_note);
    }
    header('Location: historique_film.php');

}

exit();
?>
