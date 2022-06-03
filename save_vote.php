<?php
foreach($_POST as $proposition_id=>$film_vote){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $get_proposition = $bdd->query("SELECT * FROM proposition WHERE id = '".$proposition_id."'");
    $current_proposition = $get_proposition->fetch();
    $new_score= $current_proposition['score'] - $film_vote;
    $update_proposition = $bdd->query('UPDATE proposition SET score='.$new_score.' WHERE id='.$proposition_id);
    $update_proposition->fetch();
}
echo 'Votre vote a été enregistré !';
?>