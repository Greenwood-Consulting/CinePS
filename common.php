<?php
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');

include "call_api.php";

$json_id_current_semaine = callAPI("/api/idCurrentSemaine");
$id_current_semaine = json_decode($json_id_current_semaine)->id_current_semaine;

//Fonction d'affichage
function printFilmsProposes($id_semaine){  
  echo '<h2 class="text-warning">Liste des films proposés</h2><br/>';
  
  $films_semaine = callAPI("/filmsProposes/".$id_semaine);
  $films_semaine_array = json_decode($films_semaine);
  $un_film_propose = false;
  foreach($films_semaine_array as $film){
    $un_film_propose = true;
    echo '<mark><a class="text-dark" href = '.$film->film->imdb.'>' .$film->film->titre.' </a>';
    echo $film->film->sortieFilm.'</mark></br>';
  }
  if(!$un_film_propose){//si aucun film n'est proposé
    echo '<mark> Aucun film n\'a été proposé </mark>';
  }
}

// Affiche le film victorieux
function printResultatVote($id_semaine){
    $film_victorieux = callAPI("/filmVictorieux/".$id_semaine);
    $film_victorieux_array = json_decode($film_victorieux);

    if(empty($film_victorieux_array)){//il n'y a pas de propositions 
      echo '<mark>Il n\'y a pas encore eu de propositions cette semaine</mark>';
    }else{//Affiche le film victorieux
      $film_victorieux = $film_victorieux_array[0]->film;
      echo '<mark>Tous les utilisateurs ont voté. Le film retenu est : <br ><b><a class="text-dark" href = '.$film_victorieux->imdb.'>' .$film_victorieux->titre.'</b></mark>';
    }
}

// Affichage de la liste des membres qui ont déjà voté
function printUserVote($id_semaine){
  $membre_votant = callAPI("/membreVotant/".$id_semaine);
  $membre_votant_array = json_decode($membre_votant);

  foreach($membre_votant_array as $membre){
      echo "<mark><b>".$membre->votant->nom. "</b> a voté<br /></mark>";
  }
  /*$user_vote = $bdd->prepare("SELECT votant AS votant_id FROM a_vote WHERE semaine = ?");
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
  }*/
}

//Affiche la liste de tout les proposeurs suivant la semaine $id_semaine
function printNextproposeurs($id_semaine){
  $next_proposeurs = callAPI("/nextProposeurs/".$id_semaine);
  $next_proposeurs_array = json_decode($next_proposeurs);

  foreach($next_proposeurs_array as $next){
    // création d'une DateTime afin de pouvoir formater
    $dateSemaine = DateTime::createFromFormat('Y-m-d\TH:i:sP', $next->jour);
    echo "<mark>".$dateSemaine->format('Y-m-d');
    echo " - ".$next->proposeur."</mark><br/>";
  }
}

function printChoixvote($id_semaine){
  // prenom proposeur
  $get_proposeur = callAPI("/getProposeur/".$id_semaine);
  $proposeur_prenom = json_decode($get_proposeur)[0]->proposeur;


  $get_propositions = callAPI("/filmsProposes/".$id_semaine);
  $propositions_array = json_decode($get_propositions);

  if(count($propositions_array)==0){
    echo "<p><b>Pas de proposition pour cette semaine</b> </p><br/>";
  }else{
    // Récupération de la liste des membres (pour le header)
    $get_membres = callAPI("/api/membres");
    $membres_array = json_decode($get_membres);

    $get_propositions_et_votes = callAPI("/votes/".$id_semaine);
    $array_propositions_et_votes = json_decode($get_propositions_et_votes);

    echo "<TABLE border = '1px'>";

    // Affichage du header du tableau :
    echo "<TR>";
    echo "<TD></TD><TD></TD>";
    foreach($membres_array as $data_membre){ //on crée une colonne pour chaque membre
      if($data_membre->Prenom != $proposeur_prenom){//On affiche tout le monde sauf le proposeur
        echo "<TD>";
        echo $data_membre->Prenom;
        echo "</TD>";
      }
    }
    echo "<TD>";
    echo "Score";
    echo "</TD>";
    echo "</TR>";
    // Fin affichage header

    // Affichage du corps du tableau :


    foreach($array_propositions_et_votes as $proposition_et_votes){//on crée une ligne pour chaque film de la semaine
      echo "<TR>";

      // titre avec lien imdb
      echo '<TD><a class="text-dark" href = '.$proposition_et_votes->film->imdb.'>' .$proposition_et_votes->film->titre.' </a></TD>';
      echo '<TD> '.$proposition_et_votes->film->sortieFilm.'</TD>';

      foreach($proposition_et_votes->vote as $vote){
        if($vote->membre != $proposeur_prenom){
          echo "<TD>";
          echo $vote->vote;
          echo "</TD>";
        }
      }

      // Colonne score
      echo "<TD>";
      echo $proposition_et_votes->score;
      echo "</TD>";

      echo "</TR>";
    }
    echo "</TABLE>";
  }
}


//Affiche le tableau de tout les votes de la semaine définie par $id_semaine
function printVotesSemaine($id_semaine){
  global $bdd;
  
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
    // Fin affichage header

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
