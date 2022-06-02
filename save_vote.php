<pre>
<?php
print_r($_POST);
echo $_POST['1'];
?>
</pre>
<?php
foreach($_POST as $film_id=>$film_vote){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $film = $bdd->query('SELECT * FROM film WHERE id='.$film_id);
    $requete1 = $bdd->query('SELECT * FROM proposition');
    $current_film = $requete1->fetch();
    $new_score= $current_film['score'] - $film_vote;
    $film = $bdd->query('UPDATE proposition set score='.$new_score.' WHERE   id='.$film_id);
    $film->fetch();

}
echo 'Votre vote a été enregistré !';