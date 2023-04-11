<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

// Date du jour
$curdate=new DateTime();

// calcul de la date de fin de la période de vote
$fin_periode_vote = new DateTime("Fri 14:00");
$fin_periode_vote = $fin_periode_vote->format('Y-m-d H:i:s');

// conversion de la date de fin en timestamp JavaScript
$deadline_vote = strtotime($fin_periode_vote);
$deadline_vote = $deadline_vote*1000;

// Get état id_current_semaine
if ($curdate->format('D')=="Fri"){ // Si nous sommes vendredi, alors id_current_semaine est défini par ce vendredi
  $friday_current_semaine = $curdate->format('Y-m-d');
} else { // Sinon id_current_semaine est défini par vendredi prochain
  $friday_current_semaine = $curdate->modify('next friday')->format('Y-m-d');
}
$get_semaine_id = $bdd->prepare("SELECT id FROM semaine WHERE jour = ?");
$get_semaine_id->execute([$friday_current_semaine]);
if($current_semaine = $get_semaine_id->fetch()){//La semaine en cours est défini dans la bdd
  $id_current_semaine = $current_semaine['id'];
}else{//Pas de semaine en cours défini dans la bdd
  $id_current_semaine = 0;
}

//Récupération des mails
//$requete_mail = $bdd->query("SELECT mail FROM membre");

//Affiche la liste des films proposés
function printFilmsProposes($id_semaine){
  echo '<h2 class="text-warning">Liste des films proposés</h2><br/>';
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $get_film_semaine = $bdd->prepare("SELECT film AS film_id FROM proposition WHERE semaine = ?");
  $get_film_semaine->execute([$id_semaine]);
  $un_film_propose = false;
  while ($film = $get_film_semaine->fetch()){//On affiche un film
    $un_film_propose = true;
    $ajout_film = $bdd->prepare('SELECT titre, sortie_film, imdb FROM film WHERE id = ?');
    $ajout_film->execute([$film['film_id']]);
    $data_film = $ajout_film->fetch();
    echo '<mark><a class="text-dark" href = '.$data_film['imdb'].'>' .$data_film['titre'].' </a>';
    echo $data_film['sortie_film'].'</mark></br>';
    }
    if(!$un_film_propose){//si aucun film n'est proposé
      echo '<mark> Aucun film n\'a été proposé </mark>';
    }
}

//Affiche le Film victorieux
function printResultatVote($id_semaine){
    //Récupère le film ayant le meilleur score
    $film_gagnant= $bdd->prepare("SELECT film AS id_best_film FROM proposition WHERE semaine = ? ORDER BY score DESC LIMIT 1");
    $film_gagnant->execute([$id_semaine]);

    if($data=$film_gagnant->fetch()){//si il y a des films proposés, on affiche le film avec le meilleur score
      $id_best_film=$data['id_best_film'];
      $film_retenu = $bdd->prepare('SELECT titre,imdb FROM film WHERE id = ?');
      $film_retenu->execute([$id_best_film]);
      $data_film_retenu = $film_retenu->fetch();
      echo '<mark>Tous les utilisateurs ont voté. Le film retenu est : <br ><b><a class="text-dark" href = '.$data_film_retenu['imdb'].'>' .$data_film_retenu['titre'].'</b></mark>';
    }else{//sinon il n'y a pas de propositions
      echo '<mark>Il n\'y a pas encore eu de propositions cette semaine</mark>';
    }
}

function printUserVote($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $user_vote = $bdd->prepare("SELECT votant AS votant_id FROM a_vote WHERE semaine = ?");
  $user_vote->execute([$id_semaine]);
  $une_personne_a_vote = false;
  while($data = $user_vote->fetch()){//A chaque tour un votant
    $une_personne_a_vote = true;
    $user_qui_a_vote = $data['votant_id'];
    $user_a_vote = $bdd->prepare('SELECT Prenom FROM membre WHERE id = ?');
    $user_a_vote->execute([$user_qui_a_vote]);
    echo '<mark><b>' .$user_a_vote->fetch()['Prenom'].' a voté</b></mark><br/>';
  }
  if(!$une_personne_a_vote){//Personne n'a voté
    echo '<mark>Personne n\'a voté pour l\'instant<br/></mark>';
  }
}

function printAllfilmsSemaines($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $get_film_semaine = $bdd->prepare("SELECT film AS film_id FROM proposition WHERE semaine = ?");
  $get_film_semaine->execute([$id_semaine]);
  $un_film_propose = false;
  while ($film = $get_film_semaine->fetch()){//A chaque tour de boucle on affiche l'un des films de la semaine
    $un_film_propose = true;
    $requete_titre_film = $bdd->prepare('SELECT titre, imdb FROM film WHERE id = ?');
    $requete_titre_film->execute([$film['film_id']]);
    $data_film = $requete_titre_film->fetch();
    echo '<mark><a class="text-dark" href = '.$data_film['imdb'].'>' .$data_film['titre'].' </a></br>';
  }
  if(!$un_film_propose){//Aucun film proposé pour la semaine en cours
    echo "<mark> Pas de film pour cette semaine </mark>";
  }
}
//Affiche la liste de tout les proposeurs suivant la semaine $id_semaine
function printNextproposeurs($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  $date = date('Y-m-d');
  $requete_jour_correspondant = $bdd->prepare("SELECT jour FROM semaine WHERE id = ?");
  $requete_jour_correspondant->execute([$id_semaine]);
  $jour_correspondant_id_semaine = $requete_jour_correspondant->fetch()['jour'];
  $next_proposeurs = $bdd->prepare("SELECT proposeur, jour FROM semaine WHERE jour >= ? ORDER BY jour");
  $next_proposeurs->execute([$jour_correspondant_id_semaine]);
    while ($data = $next_proposeurs->fetch()){//Tant que un proposeur est défini pour la ou les semaines suivantes
      echo '</br><mark>' .$data['jour'];
      echo " - " .$data['proposeur'].'<mark/>';
    }
}

function printChoixvote($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  
  $get_film_semaine= $bdd->prepare("SELECT id, film AS film_id FROM proposition WHERE semaine = ?");
  $get_film_semaine->execute([$id_semaine]);
  
  //$score = $get_score->fetch()['score'];
  $get_proposeur_prenom = $bdd->prepare("SELECT proposeur FROM semaine WHERE id = ?");
  $get_proposeur_prenom->execute([$id_semaine]);
  $proposeur_prenom = $get_proposeur_prenom->fetch()['proposeur'];
  $get_proposeur_id = $bdd->prepare("SELECT id FROM membre WHERE Prenom = ?");
  $get_proposeur_id->execute([$proposeur_prenom]);
  
  $proposeur_id =$get_proposeur_id->fetch()['id'];
  echo "<TABLE border = '1px'>";

  // Affichage du header du tableau :
  $get_membre_header = $bdd->query('SELECT Prenom FROM membre');
  echo "<TR>";
  echo "<TD></TD><TD></TD>";
  while ($data_membre = $get_membre_header->fetch()){//on crée une colonne pour chaque membre
    if($data_membre['Prenom']!= $proposeur_prenom){//On affiche tout le monde sauf le proposeur
      echo "<TD>";
      echo $data_membre['Prenom'];
      echo "</TD>";
    }
  }
  echo "<TD>";
  echo "Score";
  echo "</TD>";
  echo "</TR>";
  while ($proposition = $get_film_semaine->fetch()){//on crée une ligne pour chaque film de la semaine
    echo "<TR>";
    $proposition_id = $proposition['id'];
    $get_film = $bdd->prepare('SELECT titre, sortie_film, imdb FROM film WHERE id = ?');
    $get_film->execute([$proposition['film_id']]);
    
    $data_film = $get_film->fetch();
    echo '<TD><a class="text-dark" href = '.$data_film['imdb'].'>' .$data_film['titre'].' </a></TD>';
    echo '<TD> '.$data_film['sortie_film'].'</TD>';
    $get_membre = $bdd->query('SELECT id, Prenom FROM membre');
    while ($data_membre = $get_membre->fetch()){//On affiche le vote de chaque membres
      if($data_membre['Prenom']!= $proposeur_prenom){//On affiche pas le proposeur car il ne vote pas
        echo "<TD>";
        $prenom = $data_membre['Prenom'];
        $id_membre = $data_membre['id'];
        $get_vote = $bdd->prepare("SELECT vote FROM votes WHERE membre = ? AND proposition = ?");
        $get_vote->execute([$id_membre, $proposition_id]);
        if($vote = $get_vote->fetch()){//On affiche les votes
          echo $vote['vote'];
        }
        echo "</TD>";
      }
    }
    $get_score = $bdd->prepare("SELECT score FROM proposition WHERE id = ?");
    $get_score->execute([$proposition_id]);
    $score = $get_score->fetch();
    echo "<TD>";
    echo $score['score'];
    echo "</TD>";
    echo "</TR>";
  }
  echo "</TABLE>";

  echo "klfegplep".$proposition_id;
}
//Affiche le tableau de tout les votes de la semaine définie par $id_semaine
function printVotesSemaine($id_semaine){
  $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
  
  $get_film_semaine= $bdd->prepare("SELECT id, film AS film_id FROM proposition WHERE semaine = ?");
  $get_film_semaine->execute([$id_semaine]);
  if($get_film_semaine->rowCount()==0){
    echo "<p><b>Pas de proposition pour cette semaine</b> </p><br/>";
  }else{
    $get_proposeur_prenom = $bdd->prepare("SELECT proposeur FROM semaine WHERE id = ?");
    $get_proposeur_prenom->execute([$id_semaine]);
    $proposeur_prenom = $get_proposeur_prenom->fetch()['proposeur'];
    $get_proposeur_id = $bdd->prepare("SELECT id FROM membre WHERE Prenom = ?");
    $get_proposeur_id->execute([$proposeur_prenom]);
    $proposeur_id =$get_proposeur_id->fetch()['id'];
    echo "<TABLE border = '1px'>";

    // Affichage du header du tableau :
    $get_membre_header = $bdd->query('SELECT Prenom FROM membre');
    echo "<TR>";
    echo "<TD></TD><TD></TD>";
    while ($data_membre = $get_membre_header->fetch()){//on crée une colonne pour chaque membre
      if($data_membre['Prenom']!= $proposeur_prenom){//On affiche tout le monde sauf le proposeur
        echo "<TD>";
        echo $data_membre['Prenom'];
        echo "</TD>";
      } 
    }
    echo "<TR>";
    while ($proposition = $get_film_semaine->fetch()){//on crée une ligne pour chaque film de la semaine
      $proposition_id = $proposition['id'];
      $get_film = $bdd->prepare('SELECT titre, sortie_film, imdb FROM film WHERE id = ?');
      $get_film->execute([$proposition['film_id']]);
      
      $data_film = $get_film->fetch();
      echo '<TD><a href = '.$data_film['imdb'].'>' .$data_film['titre'].' </a></TD>';
      echo '<TD> '.$data_film['sortie_film'].'</TD>';
      $get_membre = $bdd->query('SELECT id, Prenom FROM membre');
      while ($data_membre = $get_membre->fetch()){//On affiche le vote de chaque membres
        if($data_membre['Prenom']!= $proposeur_prenom){//On affiche pas le proposeur car il ne vote pas
          echo "<TD>";
          $prenom = $data_membre['Prenom'];
          $id_membre = $data_membre['id'];
          $get_vote = $bdd->prepare("SELECT vote FROM votes WHERE membre = ? AND proposition = ?");
          $get_vote->execute([$id_membre, $proposition_id]);
          if($vote = $get_vote->fetch()){//On affiche les votes
            echo $vote['vote'];
          }
          echo "</TD>";
        }
      }
      //On affiche le score pour ce film
      $get_score = $bdd->prepare("SELECT score FROM proposition WHERE id = ?");
      $get_score->execute([$proposition_id]);
      $score = $get_score->fetch();
      echo "<TD>";
      echo $score['score'];
      echo "</TD>";
      echo "</TR>";
    }
    echo "</TABLE>";
  }
}
?>
