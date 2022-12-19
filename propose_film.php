<?php 
include('common.php');

$bdd = new PDO('mysql:host=localhost;dbname=CinePS','root','');
$deb= new DateTime ("Fri 20:00");
$fin = new DateTime("Sat 12:00");
$curdate=new DateTime();
$next_friday = $curdate->modify('next friday')->format('Y-m-d');
$get_id_next_friday = $bdd->prepare("SELECT id FROM semaine WHERE jour = ?");
$get_id_next_friday->execute([$next_friday]);
$current_semaine = $get_id_next_friday->fetch();
$id_current_semaine = $current_semaine['id'];

echo '<h1>Film(s) proposé(s) cette semaine</h1>';

$vote_period = true;
$get_proposition_semaine = $bdd->prepare("SELECT proposition_termine FROM semaine WHERE id = ?");
$get_proposition_semaine->execute([$id_current_semaine]);
$proposition_semaine =  $get_proposition_semaine->fetch()['proposition_termine'];


$connecte = isset($_SESSION['user']);

//Proposition comportement 1 : on vient du bouton end_proposition
if(isset($_POST['end_proposition'])){//si on appui sur le bouton "proposition terminée" ça va le mettre dans la bdd et un message s'affichera sur la fenetre
    $update_proposition_terminé = $bdd->prepare('UPDATE semaine SET proposition_termine = 1 WHERE id = ?');
    $update_proposition_terminé->execute([$id_current_semaine]);
    echo 'Les propositions a été faite pour cette semaine';
}
//Propostion comportement 2 : on vient du bouton new_proposition
if(isset($_POST['new_proposition'])){//si un nouveau film est proposé
    $titre_film = addslashes($_POST['titre_film']);
    $date = date('Y-m-d');
    $ajout_film = $bdd->prepare("INSERT INTO `film` (`id`, `titre`, `date`) VALUES (?, ?, ?");
    $ajout_film->execute(["" ,$titre_film, $date]);
    $last_id = $bdd->lastInsertId();
    $ajout_de_proposition = $bdd->prepare("INSERT INTO `proposition` (`id`, `semaine`, `film`,`score`) VALUES (?,?,?,?");
    $ajout_de_proposition->execute(["" ,$id_current_semaine, $last_id, 36]);

    echo '<br/>';
    echo '<br/>';
    echo '<br/>';
}


$connecte = true;
$vote_period = true;
//$proposition_semaine = ;
$vote_termine_cette_semaine = false;
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
                    echo 'Les propositions sont terminées mais vous n\'avez pas encore voté';
                }
            }
        }else{//les propositions de la semaine ne sont pas terminées 
            echo 'Les propositions de ne sont pas terminés';
                printFilmsProposes($id_current_semaine);
                ?>
                <form method="POST" action="propose_film.php">
                <label> Proposition de films:</label>
                <input type="text" name="titre_film">
                <?php
                echo '<button type="submit" name="new_proposition" class="btn btn-warning">Proposer un nouveau film</button> </br>';
                echo '<button type="submit" name="end_proposition" class="btn btn-warning">Proposition terminé</button> </br>';
                ?>
                </form>
<?php
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

