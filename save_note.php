<?php
include('header.php');
include('common.php');

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
      $note = addslashes(($_POST['note']));
      $id_film = $_POST['id_film'];
  
      $array_note = array(
          "film_id" => $id_film,
          "membre_id" => $membre_id,
          "note" => $note,
      );
      $json_note = json_encode($array_note);
      call_API("/api/note", "POST", $json_note);
      header('Location: historique_film.php');
}

exit();
?>
