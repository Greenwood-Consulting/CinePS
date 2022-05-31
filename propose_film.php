<!--link href="./main.3f6952e4.css" rel="stylesheet"-->
<!--div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')"-->
<?php 
include('header.php');
include('common.php');
?>
<?php
if(!isset($_SESSION['user'])){
    echo "Vous devez être connecté pour voir cette page";
}else{
    echo 'Une idée ?';
}
$bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
$deb= new DateTime ("Fri 20:00");
$fin = new DateTime("Sat 12:00");
$curdate=new DateTime();
$next_friday = $curdate->modify('next friday')->format('Y-m-d');
$requete = $bdd->query("SELECT id FROM semaine WHERE jour ='".$next_friday."'");
$current_semaine = $requete->fetch();
$id_current_semaine = $current_semaine['id'];

echo '<h1>Film(s) proposé(s) cette semaine</h1>';

//$vote_period=$curdate>=$deb && $curdate <= $fin;
$vote_period = true;
$requete1 = $bdd->query("SELECT id FROM proposition WHERE id = '".$id_current_semaine."'");
$proposition_semaine =  $requete1->fetch();



$connecte = isset($_SESSION['user']);
//Comportement 2 : on vient du bouton new_proposition
if(isset($_POST['nex_proposition'])){
//sauvegarder en base le film qui a ete proposé
$nouveau_film_propose = $_POST['film'];
//requete insert
}
function printFilmsProposes($id_semaine){
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    $requete7 = $bdd->query("SELECT film AS film_id FROM proposition WHERE semaine = '".$id_semaine."'");
    while ($film = $requete7->fetch()){
      $requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
      echo $requete6->fetch()['titre'].'<br/>';
    }
  }
    ?>
</form>
<?php



//Comportement 1 : on vient du bouton end_proposition
if(isset($_POST['end_proposition'])){
    //Mettre à jour le champ proposition terminé dans la table semaine afin d'indiquer que la semaine est terminé
    $requete6 = $bdd->query('UPDATE semaine SET proposition_termine = 1 WHERE id ='.$id_current_semaine);
    echo 'Les propositions a été faite pour cette semaine';
}
//Comportement 2 : on vient du bouton new_proposition
if(isset($_POST['new_proposition'])){
//sauvegarder en base le film qui a ete proposé 
//une requete insert dans la table film pour ajouter le nouveau film
$titre_film = $_POST['titre_film'];
$date = date('Y-m-d');
$requete6 = $bdd->query("INSERT INTO `film` (`id`, `titre`, `date`) VALUES ('', '".$titre_film."','".$date."')");
$last_id = $bdd->lastInsertId();
$requete7 = $bdd->query("INSERT INTO `proposition` (`id`, `semaine`, `film`,`score`) VALUES ('', '".$id_current_semaine."','".$last_id."','36')");

//une autre requete insert dans la table proposition pour ajouter la proposition de ce film 

//requete insert
echo '<br/>';
echo '<br/>';
echo '<br/>';
}
/*$connecte = true;
$vote_period = true;
$proposition_semaine = false;
$vote_termine_cette_semaine = true;*/
if($connecte){//l'utilisateur est connecté
    if($vote_period){//nous sommes en période de vote
        if($proposition_semaine){//les propositions ont été faite
            if($vote_termine_cette_semaine){//le vote est terminé
                printFilmsProposes($id_current_semaine);
                printResultatVote($id_current_semaine); 
            }else{//le vote n'est pas terminé
                if($vote_termine_cette_semaine){//l'user a vote
                    echo '<br/>';
                    printFilmsProposes($id_current_semaine); 
                    echo 'Vous avez deja voté';
                }else{//l'user n'a pas voté
                    echo '<br/>';
                    printFilmsProposes($id_current_semaine); 
                    echo 'Vous n\'avez pas encore voté';
                }
            }
        }else{//aucune propositions faites
            echo 'Aucun film n\'a été proposé cette semaine. Veuillez faire des propositions';
            ?>
            <form method="POST" action="propose_film.php">
    <label> Proposition de films:</label>
    <input type="text" name="titre_film">
    <?php
    echo '<button type="submit" name="new_proposition" class="btn btn-warning">Proposer un nouveau film</button> </br>';
    echo '<button type="submit" name="end_proposition" class="btn btn-warning">Proposition terminé</button> </br>';

        }
    }else{//nous ne sommes pas en période de vote
        echo 'il n\'est pas encore possible de faire une nouvelle proposition';
    }
}else{//l'utilisateur n'est pas connecté
    if($vote_period){//l'utilisayeur n'est pas connecté mais en période de vote
        if($proposition_semaine){//pas connecté mais la proposition est faite
            if($vote_termine_cette_semaine){//pas connecté mais vote terminé
                printFilmsProposes($id_current_semaine);
                printResultatVote($id_current_semaine);
            }else{//pas connecté et vote pas terminé
                printFilmsProposes($id_current_semaine);
            }
        }else{//pas connecté et proposition non faite
            echo'La proposition n\'a pas encore été faite pour cette semaine. Vous devez vous connecter pour proposer';
        }
    }else{//l'utilisayeur n'est pas connecté et pas en période de vote
        echo'Il n\'est pas encore possible de faire une nouvelle proposition';
    }
}

