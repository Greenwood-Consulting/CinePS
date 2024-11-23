<?php

include "call_api.php";

$json_current_semaine = call_API_GET("/api/currentSemaine");
$array_current_semaine = json_decode($json_current_semaine);
$id_current_semaine = $array_current_semaine[0]->id;


//Fonction d'affichage
function printFilmsProposes($id_semaine){
  global $array_current_semaine;
  echo '<h2 class="text-warning">Liste des films proposés</h2><br/>';

  if (empty($array_current_semaine[0]->propositions) || !$array_current_semaine[0]->proposition_termine) { // Aucun film n'a été proposé ou proposition non terminée
    echo '<mark> Aucun film n\'a été proposé </mark>';
  } else {
    foreach($array_current_semaine[0]->propositions as $proposition){
      echo '<mark><a class="text-dark" href = '.$proposition->film->imdb.'>' .$proposition->film->titre.' </a>';
      echo $proposition->film->sortie_film.'</mark></br>'; 
    }
  }
}

// Affiche le film victorieux
function printResultatVote($id_semaine){
    $film_victorieux = call_API_GET("/api/filmVictorieux/".$id_semaine);
    $film_victorieux_array = json_decode($film_victorieux);
    if(empty($film_victorieux_array)){//il n'y a pas de propositions 
      echo '<mark>Il n\'y a pas encore eu de propositions cette semaine</mark>';
    }elseif(count($film_victorieux_array) == 1){//Affiche le film victorieux
      $film_victorieux = $film_victorieux_array[0]->film;
      echo '<mark>Tous les utilisateurs ont voté. Le film retenu est : <br ><b><a class="text-dark" href = '.$film_victorieux->imdb.'>' .$film_victorieux->titre.'</b></mark>';
    }else{
      $film_victorieux = $film_victorieux_array[0]->film;
      echo '<mark>Tous les utilisateurs ont voté. Il y a égalité entre les films suivants : <br/>';
      foreach($film_victorieux_array as $film_egalite) {
        echo $film_egalite->film->titre.'<br/>';
      }
      echo '</mark>';
    }
}

// Affichage de la liste des membres qui ont déjà voté
function printUserAyantVote(){
  $current_semaine_json = call_API_GET("/api/currentSemaine");
  $current_semaine_array = json_decode($current_semaine_json);
  $votants_array = $current_semaine_array[0]->votants;
  

  foreach($votants_array as $votant){
    echo "<mark><b>".$votant->votant->Nom. "</b> a voté<br/></mark>";
  }
  if(empty($votants_array)){//Personne n'a voté
    echo '<mark>Personne n\'a voté pour l\'instant<br/></mark>';
  }
}

//Affiche la liste de tout les proposeurs suivant la semaine $id_semaine
function printNextproposeurs($id_semaine){
  $next_proposeurs = call_API_GET("/api/nextProposeurs/".$id_semaine);
  $next_proposeurs_array = json_decode($next_proposeurs);

  foreach($next_proposeurs_array as $semaine){
    // création d'une DateTime afin de pouvoir formater
    $dateSemaine = DateTime::createFromFormat('Y-m-d\TH:i:sP', $semaine->jour);
    echo "<mark>".$dateSemaine->format('Y-m-d');
    if ($semaine->type == 'PSAvecFilm'){
      echo " - ".$semaine->proposeur->Nom."</mark>";
    }
    if ($semaine->type == 'PSSansFilm'){
      echo " - Pas de Film 🥂</mark>";
    }
    if ($semaine->type == 'PasDePS'){
      echo " - Pas de PS 😴</mark>";
    }
    echo "<br/>";
  }
}

function printChoixvote($id_semaine){
  $proposeur_prenom -> $current_semaine_array[0]->proposeur->Prenom;

  $propositions_array = $current_semaine_array[0]->propositions;
 

  if(count($propositions_array)==0){
    echo "<p><b>Pas de proposition pour cette semaine</b> </p><br/>";
  }else{
    // Récupération de la liste des membres (pour le header)
    $get_membres = call_API_GET("/api/membres");
    $membres_array = json_decode($get_membres);

    // Récupération des propositions avec votes
    $get_propositions_et_votes = call_API_GET("/api/votes/".$id_semaine);
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

      $get_film_gagnant = call_API_GET("/api/filmVictorieux/".$id_semaine);
      $film_gagnant_array = json_decode($get_film_gagnant);

      $id_proposition = $proposition_et_votes->id;
      $id_film = $proposition_et_votes->film->id;

      if($film_gagnant_array[0]->id == $id_proposition){

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
          echo "<form method='POST' action='save_note.php'>";

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
    $proposeur_prenom = $array_semaine->proposeur->Prenom;
  
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
        if($data_membre->Prenom != $proposeur_prenom){//On affiche tout le monde sauf le proposeur
          echo "<TD>";
          echo $data_membre->Prenom;
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
    
          if($film_victorieux_id == $id_proposition){
            // Parcourir le tableau des notes et calcul de la moyenne
            $nb_notes = 0;
            $current_user_a_note = false;
            for ($i = 0; $i < count($proposition_et_votes->note); $i ++)
            {
              if(is_int($proposition_et_votes->note[$i]->note)){
                if($proposition_et_votes->note[$i]->membre == $_SESSION['user']){
                  $current_user_a_note = true;
                }
                $sumOfNotes= $sumOfNotes + $proposition_et_votes->note[$i]->note;
                $nb_notes = $nb_notes + 1;
              }
            }
    
            if(!$current_user_a_note){
              echo "<form method='POST' action='save_note.php'>";
    
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