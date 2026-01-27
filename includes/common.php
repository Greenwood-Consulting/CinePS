<?php



//Fonction d'affichage
function printFilmsProposes(){
  global $json_current_semaine;
  global $proposition_semaine;
  global $is_proposeur;
  echo '<h2 class="text-warning">Liste des films proposés par ' . $json_current_semaine->proposeur->nom . '</h2><br/>';

  if (empty($json_current_semaine->propositions)) { // Aucun film n'a été proposé
    echo '<mark> Aucun film n\'a été proposé </mark>';
  } else {
    echo '<form method="POST" action="/index.php" onsubmit="return confirm(\'Supprimer la proposition?\')">';
    foreach ($json_current_semaine->propositions as $proposition) {
      echo '<div>';
      echo '<mark><a class="text-dark" href="' . $proposition->film->imdb . '">' . $proposition->film->titre . ' </a>';
      echo $proposition->film->sortie_film;
      echo '</mark>';
      if ($is_proposeur && !$proposition_semaine) {
        echo '<button type="submit" name="delete_proposition" value="' . $proposition->id . '" class="btn" >🗑️</button>';
    }
      echo '</div>';
    }
    echo '</form>';
  }
}

// Affiche le film victorieux
function printResultatVote($id_semaine){
  $json_film_victorieux = call_API("/api/filmVictorieux/".$id_semaine, "GET");
  // @TOTO : est-ce qu'on ne peut pas récupérer le film victorieux directement depuis $json_current_semaine ?
  if(empty($json_film_victorieux)){//il n'y a pas de propositions 
    echo '<mark>Il n\'y a pas encore eu de propositions cette semaine</mark>';
  }elseif(count($json_film_victorieux) == 1){//Affiche le film victorieux
    $film_victorieux = $json_film_victorieux[0]->film;
    echo '<mark>Tous les utilisateurs ont voté. Le film retenu est : <br ><b><a class="text-dark" href = '.$film_victorieux->imdb.'>' .$film_victorieux->titre.'</b></mark>';
  }else{
    $film_victorieux = $json_film_victorieux[0]->film;
    echo '<mark>Tous les utilisateurs ont voté. Il y a égalité entre les films suivants : <br/>';
    foreach($json_film_victorieux as $film_egalite) {
      echo $film_egalite->film->titre.'<br/>';
    }
    echo '</mark>';
  }
}

// Affichage de la liste des membres qui ont déjà voté
function printUserAyantVote(){
  global $json_current_semaine;
  $votants_array = $json_current_semaine->votants;
  
  foreach($votants_array as $votant){
    echo "<mark><b>".$votant->votant->nom. "</b> a voté<br/></mark>";
  }
  if(empty($votants_array)){//Personne n'a voté
    echo '<mark>Personne n\'a voté pour l\'instant<br/></mark>';
  }
}

//Affiche la liste de tout les proposeurs suivant la semaine $id_semaine
function printNextproposeurs($id_semaine){
  $json_next_proposeurs = call_API("/api/nextProposeurs/".$id_semaine, "GET");

  foreach($json_next_proposeurs as $semaine){
    // création d'une DateTime afin de pouvoir formater (la timezone sera UTC si non precisée)
    $dateSemaine = new DateTime($semaine->jour, new DateTimeZone('UTC'));
    // Passage au fuseau horaire de Paris
    $dateSemaine->setTimezone(new DateTimeZone('Europe/Paris'));

    echo "<mark>".$dateSemaine->format('Y-m-d');

    switch ($semaine->type) {
      case 'PSAvecFilm':
          echo " - " . $semaine->proposeur->nom . " 🎞️";
          break;
      case 'PSSansFilm':
          echo " - Pas de Film 🥂";
          break;
      case 'PasDePS':
          echo " - Pas de PS 😴";
          break;
      case 'PSDroitDivin':
          echo " - PS de droit divin 👑";
          break;
      default:
        echo " - PS de type inconnu ⁉️";
        break;
    }

    echo "</mark>";
    echo "<br/>";
  }
}

function printChoixvote($id_semaine){
  global $json_current_semaine;
  global $membres;

  $proposeur_prenom = $json_current_semaine->proposeur->prenom;

  $propositions_array = $json_current_semaine->propositions;

  if(count($propositions_array)==0){
    echo "<p><b>Pas de proposition pour cette semaine</b> </p><br/>";
  }else{
    // Récupération des propositions avec votes
    // @TODO : renommer $array_propositions_et_votes en $json_propositions_et_votes
    $array_propositions_et_votes = call_API("/api/votes/".$id_semaine, "GET");

    echo "<TABLE border = '1px'>";
   
    // Affichage du header du tableau :
    echo "<TR>";
    echo "<TD></TD><TD></TD>";
    foreach($membres as $data_membre){ //on crée une colonne pour chaque membre
      if($data_membre->prenom != $proposeur_prenom){//On affiche tout le monde sauf le proposeur
        echo "<TD>";
        echo $data_membre->prenom;
        echo "</TD>";
      }
    }
    echo "<TD>";
    echo "Score";
    echo "</TD>";
    echo "<TD>";
    echo "Note";
    echo "</TD>";
    echo "</TR>";

  

    // Fin affichage header
    
    // Affichage du corps du tableau :
    foreach($array_propositions_et_votes as $proposition_et_votes){//on crée une ligne pour chaque film de la semaine
      echo "<TR>";

      // titre avec lien imdb
      echo '<TD><a class="text-dark" href = '.$proposition_et_votes->film->imdb.'>' .$proposition_et_votes->film->titre.' </a></TD>';
      echo '<TD> '.$proposition_et_votes->film->sortie_film.'</TD>';

      // Ajoutez une variable pour stocker la somme des notes
      $sumOfNotes = 0;

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
      //Colonne Note
      echo "<TD>";

      $json_film_gagnant = call_API("/api/filmVictorieux/".$id_semaine, "GET");

      $id_proposition = $proposition_et_votes->id;
      $id_film = $proposition_et_votes->film->id;

      if($json_film_gagnant[0]->id == $id_proposition){

        // Parcourir le tableau des notes et calcul de la moyenne
        $nb_notes = 0;
        $current_user_a_note = false;
        for ($i = 0; $i < count($array_propositions_et_votes[0]->note); $i ++)
        {
          if(is_int($array_propositions_et_votes[0]->note[$i]->note)){
            if($array_propositions_et_votes[0]->note[$i]->membre == $_SESSION['user']){
              $current_user_a_note = true;
            }
            $sumOfNotes= $sumOfNotes + $array_propositions_et_votes[0]->note[$i]->note;
            $nb_notes = $nb_notes + 1;
          }
        }


          // Calculer la moyenne
          if($nb_notes !== 0){
            $moyenne = $sumOfNotes/ $nb_notes;
          }


          if(!$current_user_a_note){
          echo "<form method='POST' action='/save_note.php'>";

          echo '<select name="note" id="'.$id_film.'">';
          echo '<option value="1">1</option>';
          echo '<option value="2">2</option>';
          echo '<option value="3">3</option>';
          echo '<option value="4">4</option>';
          echo '<option value="5">5</option>';
          echo '<option value="6">6</option>';
          echo '<option value="7">7</option>';
          echo '<option value="8">8</option>';
          echo '<option value="9">9</option>';
          echo '<option value="10">10</option>';
          echo '</select>';
          echo "<button type='submit' name='id_film' value='".$id_film."'>Noter</button>";
          echo "</TD>";
          echo "</form>";

          }else{
            echo $moyenne;
          }
      }
  
  echo "</TR>";
}

echo "</TABLE>";
    }

  }



  function printChoixvoteFromArray($array_semaine, $array_historique_membres){

    // prenom proposeur
    $proposeur_prenom = $array_semaine->proposeur->prenom;
  
    // récupération des propositions
    $get_propositions = $array_semaine->propositions;

    if ($array_semaine->filmVu != null){
      $film_victorieux_id = $array_semaine->filmVu->id;
    } else {
      if ($array_semaine->film_victorieux != null){
        $film_victorieux_id = $array_semaine->film_victorieux[0]->id;
      } else {
        $film_victorieux_id = null;
      }
    }
  
    if(count($get_propositions)==0){
      echo "<p><b>Pas de proposition pour cette semaine</b> </p><br/>";
    }else{
  
      // Récupération des propositions avec votes
      $array_propositions_et_votes = $array_semaine->propositions;
  
      echo "<TABLE border = '1px'>";
     
      // Affichage du header du tableau :
      echo "<TR>";
      echo "<TD></TD><TD></TD>";
      foreach($array_historique_membres as $data_membre){ //on crée une colonne pour chaque membre
        if($data_membre->prenom != $proposeur_prenom){//On affiche tout le monde sauf le proposeur
          echo "<TD>";
          echo $data_membre->prenom;
          echo "</TD>";
        }
      }
      echo "<TD>";
      echo "Score";
      echo "</TD>";
      if(isset($_SESSION['user'])){ // On affiche la colonne Note seulement si l'utilisateur est connecté
        echo "<TD>";
        echo "Note";
        echo "</TD>";
      }
      echo "</TR>";
      // Fin affichage header
      
      // Affichage du corps du tableau :
      foreach($array_propositions_et_votes as $proposition_et_votes){//on crée une ligne pour chaque film de la semaine
        $id_proposition = $proposition_et_votes->id;
        $id_film = $proposition_et_votes->film->id;

        if($film_victorieux_id == $id_proposition){
          echo "<TR class=\"film-victorieux\">";
        } else {
          echo "<TR>";
        }

  
        // titre avec lien imdb
        echo '<TD><a class="texte-film-victorieux" href = '.$proposition_et_votes->film->imdb.'>' .$proposition_et_votes->film->titre.' </a></TD>';
        echo '<TD> '.$proposition_et_votes->film->sortie_film.'</TD>';
  
        // Ajoutez une variable pour stocker la somme des notes
        $sumOfNotes = 0;
  
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

        //Colonne Note
        if(isset($_SESSION['user'])){ // On affiche la colonne Note seulement si l'utilisateur est connecté
          echo "<TD>";
    
          if($film_victorieux_id == $id_proposition){ // il n'y a une note que pour le film victorieux
            
            $nb_notes = 0;
            $current_user_a_note_et_non_absention = false;
            for ($i = 0; $i < count($proposition_et_votes->note); $i ++)
            { // Parcourir le tableau des notes et calcul de la moyenne
              if(is_int($proposition_et_votes->note[$i]->note)){ // il y a une note
                if($proposition_et_votes->note[$i]->membre == $_SESSION['user']){
                  $current_user_a_note_et_non_absention = true;
                }
                $sumOfNotes= $sumOfNotes + $proposition_et_votes->note[$i]->note;
                $nb_notes = $nb_notes + 1;
              }
              if (is_null($proposition_et_votes->note[$i]->note) && $proposition_et_votes->note[$i]->membre == $_SESSION['user']){ // abtenstion
                $current_user_a_note_et_non_absention = true;
              }
            }
    
            if(!$current_user_a_note_et_non_absention){
              echo "<form method='POST' action='/save_note.php'>";
    
              echo '  <select name="note" id="'.$id_film.'">';
              echo '    <option value="0">0 - Christophe Barbier</option>';
              echo '    <option value="1">1 - Purge</option>';
              echo '    <option value="2">2 - A chier liquide par terre</option>';
              echo '    <option value="3">3 - Nul</option>';
              echo '    <option value="4">4 - Bof</option>';
              echo '    <option value="5">5 - Ca passe</option>';
              echo '    <option value="6">6 - Moyen</option>';
              echo '    <option value="7">7 - Bon</option>';
              echo '    <option value="8">8 - Très bon</option>';
              echo '    <option value="9">9 - Borderline Chef d\'oeuvre</option>';
              echo '    <option value="10">10 - Chef d\'oeuvre</option>';
              echo '    <option value="11">11 - Up to eleven</option>';
              echo '    <option value="abs">Ne pas noter</option>';
              echo '  </select>';
              echo "  <button type='submit' name='id_film' value='".$id_film."'>Noter</button>";
              echo "</form>";
  
            }else{
              if($nb_notes !== 0){
                $moyenne = round($sumOfNotes/ $nb_notes, 1); // Calculer la moyenne
                echo "<b>".$moyenne."</b> (".$nb_notes." notes)";
              }
            }
          }
          echo "</TD>";
          // Fin de la colonne sur les notes
        }
    
    echo "</TR>";
  }
  
  echo "</TABLE>";
      }
  
    }
?>