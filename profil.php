<?php
include('header.php');
$bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>
<body>
    <?php
    if(isset($_SESSION['user'])){//Si l'user est connecté on affiche ses données personnelles
        $profil_connecte =$bdd->prepare("SELECT Nom, Prenom, mail, mdp FROM membre WHERE Prenom = ?");
        $profil_connecte->execute([$_SESSION['user']]);
        $data_profil_connecte = $profil_connecte->fetch();
        echo $data_profil_connecte['Nom'].' '. $data_profil_connecte['Prenom'].' '. $data_profil_connecte['mail'];
    }else header('Location:index.php');//Sinon il est redirigé directement vers l'index
    ?>
    <h2>Chnager de mot de passe</h2>
    <form method='POST' action='#'>
        <div class="form-outline mb-4">
            <input type="password" name="old_password" class="form-control" />
            <label class="form-label" for="form2Example2">Ancien mot de passe</label>
        </div>
        <div class="form-outline mb-4">
            <input type="password" name="new_password" class="form-control" />
            <label class="form-label" for="form2Example2">Nouveau mot de passe</label>
        </div>
        <button type="submit" name='connect'>Changer le mdp</button>
    </form>
    <?php
    if(isset($_POST['old_password'])){//Si l'ancien mot de passé est rentré
        if($_POST['old_password'] == $data_profil_connecte['mdp']){//Si l'ancien mot de passe correspond le nouveau de mot de passe sera modifié
            $changement_mdp = $bdd->prepare("UPDATE membre SET mdp ='".$_POST['new_password']."' WHERE Prenom = ?");
            $changement_mdp->execute([$_SESSION['user']]);
            echo "Le mot de passe a bien été modfié";
        }else{//Sinon le mot de passe ne sera pas modifié
            echo "Pour être modifié vous devez saisir l'ancien mot de passe correctement";
        }
    }
    
    ?>
</body>
</html>