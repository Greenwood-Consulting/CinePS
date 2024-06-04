<?php

$bdd = new PDO('mysql:host=localhost;dbname=cineps_prod','root','');
include('call_api.php');

$get_table_membre = $bdd->query('SELECT * FROM membre');
while ($table_membre= $get_table_membre->fetch()){//On affiche un film
    $array_membre = array(
        "nom" => $table_membre['Nom'],
        "prenom" => $table_membre['Prenom'],
        "mail" => $table_membre['mail'],
        "mdp" => $table_membre['mdp']
    );
    $json_membre = json_encode($array_membre);
    $api_membre = callAPI_POST("/api/newmembre", $json_membre);
}

$get_table_semaine = $bdd->query('SELECT * FROM semaine');
while ($table_semaine= $get_table_semaine->fetch()){//On affiche un film
    echo "Semaine : ".$table_semaine['id']."\n"; // trace de l'id semaine pour pouvoir débuguer

    $prenom = $table_semaine['proposeur'];
    
    $json_membre= callAPI("/api/membre/". $prenom);
    $array_membre = json_decode($json_membre);
    
    if ($array_membre){
        $id_membre = $array_membre->id;

        $array_semaine = array(
            "jour" => $table_semaine['jour'],
            "proposeur_id" => $id_membre,
            "proposition_termine" => $table_semaine['proposition_termine'],
            "theme" => $table_semaine['theme']
        );
    
        //Création de la semaine
        $json_semaine = json_encode($array_semaine);
        $api_semaine = callAPI_POST("/api/newSemaine", $json_semaine);
        $json_api_semaine = json_decode($api_semaine);
    
        //MAJ du thème et de la proposition terminée
        $id_semaine_creee = $json_api_semaine->id;
        $array_patch_semaine = array(
            "proposition_terminee" => $table_semaine['proposition_termine'],
            "theme" => $table_semaine['theme']
        );
    
        $json_patch_semaine = json_encode($array_patch_semaine);
        $api_patch_semaine = callAPI_PATCH("/api/semaine/". $id_semaine_creee, $json_patch_semaine);
        
        //Création des propositions de la semaine qui vient d'être créée
        $get_table_proposition = $bdd->query('SELECT * FROM proposition WHERE semaine ='. $table_semaine['id']);
        while ($table_proposition = $get_table_proposition->fetch()) {
            $get_film = $bdd->query('SELECT * FROM film WHERE id = '. $table_proposition['film']);
            $table_film = $get_film->fetch();
            $array_film = array(
                "titre_film" => $table_film['titre'],
                "sortie_film" => $table_film['sortie_film'],
                "imdb_film" => $table_film['imdb'],
                "id_semaine" => $id_semaine_creee
            );
            
            $json_film = json_encode($array_film);
            $api_film = callAPI_POST("/api/propositionMigration", $json_film);
            $json_proposition_creee = json_decode($api_film);

    
            $get_table_vote = $bdd->query('SELECT * FROM votes WHERE proposition = '.$table_proposition['id']);
            
            while ($table_vote= $get_table_vote->fetch()){                
    
                $get_id_membre = $bdd->query('SELECT * FROM membre WHERE id = '.$table_vote['membre']);
                $prenom_votant = $get_id_membre->fetch()['Prenom'];
                $json_membre_votant= callAPI("/api/membre/". $prenom_votant);
    
    
                $array_membre_votant = json_decode($json_membre_votant);
            
                $id_membre_votant = $array_membre_votant->id;
    
                $array_vote = array(
                    "membre" => $id_membre_votant,
                    "proposition" => $json_proposition_creee->id,
                    "vote" => $table_vote['vote'],
                    "id_semaine" => $id_semaine_creee
                );
    
                $json_vote = json_encode($array_vote);
                $api_vote = callAPI_POST("/api/saveVotePropositionMigration", $json_vote);
    
                
            }
        }
    
        //Migration de la table avote
        $get_table_avote = $bdd->query('SELECT * FROM a_vote WHERE semaine ='.$table_semaine['id']);
    
        while ($table_avote= $get_table_avote->fetch()){
    
            $get_membre = $bdd->query('SELECT * FROM membre WHERE id = '.$table_avote['votant']);
            $prenom_votant = $get_membre->fetch()['Prenom'];
            $json_membre_votant= callAPI("/api/membre/". $prenom_votant);
    
    
            $array_membre_votant = json_decode($json_membre_votant);
        
            $id_membre_votant = $array_membre_votant->id;
            
            $array_avote = array(
                "membre" => $id_membre_votant,
                "id_semaine" => $id_semaine_creee
            );
    
            $json_avote = json_encode($array_avote);
            $api_avote = callAPI_POST("/api/avoteMigration", $json_avote);
        }
    }

}




?>