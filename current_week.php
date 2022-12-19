<?php
$bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
  $requete_semaine_laplustardive = $bdd->query('SELECT * FROM semaine ORDER BY date DESC LIMIT 1');
  
      //On récupère la semaine qui a la date la plus tardive
 $semaine_laplustardive = $requete_semaine_laplustardive->fetch();
      setlocale (LC_TIME, 'fr_FR.utf8','fra');
      echo $semaine_laplustardive['proposeur']." ". strftime('%d %B %Y', strtotime($semaine_laplustardive['date']))."<br/>";

      //Récuperer les propositions de la semaine en cours 
      $requete_propositions_de_la_semaine = $bdd->prepare('SELECT * FROM proposition WHERE semaine = ?');
      $requete_proposition_de_la_semaine->execute([$semaine_laplustardive["id"]]);
      echo 'Les films de cette semaine : <br/>';     
      while($proposition=$requete_propositions_de_la_semaine->fetch()){//tant que $proposition = $requete_proposition_de_la_semaine on affiche les films
        $requete_titre_film = $bdd->prepare('SELECT titre FROM film WHERE id= ?');
        $requete_titre_film->execute([$proposition['film']]);
        $titre = $requete_titre_film->fetch()['titre'];
        echo $titre.'<br/>';
        

      };
  
?>