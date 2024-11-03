<?php
include('common.php');
include('header.php');

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
            callAPI_POST("/api/note", $json_note);
        }
    }
    header('Location: profil.php');
}

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
      callAPI_POST("/api/note", $json_note);
      header('Location: historique_film.php');
}

exit();
?>
