    <form method="POST" action="save_vote.php">
      
      <?php
    echo '<h1>Votez pour le film</h1>';
    $bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
    $requete = $bdd->query('SELECT * FROM film');
        
    while($data = $requete->fetch()){
        $date = new DateTime($data['date']);
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        echo $data['titre']." ".$date = strftime('%d %B %Y').'<input type="number" min="1" max="6">'."<br/>";
    }
    // Liste des membres
  
    //echo '<h1 style="color:#FFF000"> Membre </h1>';
    $requete->closeCursor();
    ?>
    </input>
    <button type="submit">Voter</button>
    </form>
    