<?php 
include('header.php') 
?>
<?php
if(!isset($_SESSION['user'])){
    echo "Vous devez être connecté pour voir cette page";
}else{
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
    <?php
    echo '<button type="submit">Ajouter un film</button>';
}
    ?>
</form>
