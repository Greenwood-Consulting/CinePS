    <form method="POST" action="save_vote.php">
      
      <?php
      include('common.php');
    echo '<h1>Votez pour le film</h1>';
    
    // Liste des membres
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete7 = $bdd->query("SELECT film AS film_id FROM proposition WHERE semaine = '".$id_current_semaine."'");
    echo 'Voici la liste des films proposés <br/>';
    while ($film = $requete7->fetch()){//tant que $film = $requete 7 on affiche le tableau de vote
      $requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
      $titre_film = $requete6->fetch()['titre'];
      echo $titre_film.'<input type="number" name="'.$film['film_id'].'" value="'.$film['film_id'].'" min="1" max="6">'."<br/>";
    }



    //COULEUR A GARDER echo '<h1 style="color:#FFF000"> Membre </h1>';
    $requete->closeCursor();
    ?>
    </input>
    <button type="submit">Voter</button>
    </form>
    