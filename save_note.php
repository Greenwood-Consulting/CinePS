<?php
session_start();
include('common.php');
include('header.php');


if(isset($_POST['id_proposition'])){
      $membre_id = ($_SESSION['user']);
      $note = addslashes(($_POST['note']));
      $id_proposition = $_POST['id_proposition'];
  
      $array_note = array(
          "proposition_id" => $id_proposition,//$id_proposition,
          "membre_id" => $membre_id,
          "note" => $note,
      );
      $json_note = json_encode($array_note);
      callAPI_POST("/api/note", $json_note);
}
header('Location: historique_film.php');
exit();
?>
