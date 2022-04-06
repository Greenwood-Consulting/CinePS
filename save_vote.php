<pre>
<?php
print_r($_POST);
echo $_POST['1'];
?>
</pre>
<?php
foreach($_POST as $film_id=>$film_vote){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete = $bdd->query('SELECT * FROM film WHERE id='.$film_id);
    $current_film = $requete->fetch();
    $new_score= $current_film['score'] - $film_vote;
    $requete = $bdd->query('UPDATE film set score='.$new_score.' WHERE   id='.$film_id);
    $requete->fetch();

}
echo 'Votre vote a été enregistré !';