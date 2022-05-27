<!--link href="./main.3f6952e4.css" rel="stylesheet"-->
<!--div class="hero-full-container background-image-container white-text-container" style="background-image: url('./assets/images/space.jpg')"-->
<?php 
include('header.php') 
?>
<?php
if(!isset($_SESSION['user'])){
    echo "Vous devez être connecté pour voir cette page";
}else{
    echo 'Une idée ?';
}

  
echo '<h1>Film(s) proposé(s)</h1>';
$bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
$requete = $bdd->query('SELECT * FROM film');
    
while($data = $requete->fetch()){
    $date = new DateTime($data['date']);
    setlocale (LC_TIME, 'fr_FR.utf8','fra');
    echo $data['titre']." ".$date->format('d F Y')."<br/>";
}
    ?>
</form>
<?php
$connecte = true;
$vote_period = true;
$proposition_semaine = false;
$vote_termine = true;
$user_vote = true;

/*function printAffichedelasemaine();
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
$requete6 = $bdd->query('SELECT titre FROM film WHERE id = '.$film['film_id']);
echo $requete6->fetch()['titre'].'<br/>';*/

echo '<br/>';
echo '<br/>';
echo '<br/>';

if($connecte){//l'utilisateur est connecté
    if($vote_period){//nous sommes en période de vote
        if($proposition_semaine){//les propositions ont été faite
            if($vote_termine){//le vote est terminé
                echo'la sélection de film de cette semaine est la suivante: la selection ... le film retenu est ...';
            }else{//le vote n'est pas terminé
                if($user_vote){//l'user a vote
                    echo 'La selection est ... Vous avez deja voté';
                }else{//l'user n'a pas voté
                    echo 'La selection est ... Vous n\'avez pas encore vote';
                }
            }
        }else{//aucune propositions faites
            ?>
            <form method="POST" action="save_film.php">
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit" class="btn btn-warning">Ajouter un film</button> </br>';
    ?>
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit" class="btn btn-warning">Ajouter un film</button></br>';
    ?>
     <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit" class="btn btn-warning">Ajouter un film</button> </br>';
    ?>
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit" class="btn btn-warning">Ajouter un film</button> </br>';
    ?>
    <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit" class="btn btn-warning">Ajouter un film</button></br>';
    ?>
     <label> Proposition de films:</label>
    <input type="text" name="film">
    <?php
    echo '<button type="submit" class="btn btn-warning">Ajouter un film</button>';
        }
    }else{//nous ne sommes pas en période de vote
        echo 'il n\'est pas encore possible de faire une nouvelle proposition';
    }
}else{//l'utilisateur n'est pas connecté
    if($vote_period){//l'utilisayeur n'est pas connecté mais en période de vote
        if($proposition_semaine){//pas connecté mais la proposition est faite
            if($vote_termine){//pas connecté mais vote terminé
                echo'la sélection de film de cette semaine est la suivante: la selection ... le film retenu est ...';
            }else{//pas connecté et vote terminé
                echo'la selection de la semaine est ...';
            }
        }else{//pas connecté et proposition non faite
            echo'La proposition n\'a pas encore été faite pour cette semaine. Vous devez vous connecter pour proposer';
        }
    }else{//l'utilisayeur n'est pas connecté et pas en période de vote
        echo'Il n\'est pas encore possible de faire une nouvelle proposition';
    }
}

