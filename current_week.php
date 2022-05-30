<?php
$bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
  $requete_semaine_laplustardive = $bdd->query('SELECT * FROM semaine ORDER BY date DESC LIMIT 1');
  
      //On récupère la semaine qui a la date la plus tardive
 $semaine_laplustardive = $requete_semaine_laplustardive->fetch();
      setlocale (LC_TIME, 'fr_FR.utf8','fra');
      echo $semaine_laplustardive['proposeur']." ". strftime('%d %B %Y', strtotime($semaine_laplustardive['date']))."<br/>";

      //Récuperer les propositions de la semaine en cours 
      $requete_propositions_de_la_semaine = $bdd->query('SELECT * FROM proposition WHERE semaine = '.$semaine_laplustardive["id"]);
      echo 'Les films de cette semaine : <br/>';     
      while($proposition=$requete_propositions_de_la_semaine->fetch()){
        $requete_titre_film = $bdd->query('SELECT titre FROM film WHERE id='.$proposition['film']);
        $titre = $requete_titre_film->fetch()['titre'];
        echo $titre.'<br/>';
        

      };
  
?>