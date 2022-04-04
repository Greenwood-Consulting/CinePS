<?php
//On salut le membre*
session_start();
if(isset($_POST['user'])){
    $_SESSION['user']=$_POST['user'];
}


echo "Bonjour ".$_SESSION['user'];



?>

<form method="POST" action="save_film.php">
    <!--label> Proposition de films:</label>
    <input type="text" name="film">
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <label> Proposition de films:</label>
    <input type="text" name="film"-->
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <button type="submit">Ajouter un film</button>
</form>