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
    if(isset($_SESSION['user'])){
        $profil_connecte =$bdd->query("SELECT Nom, Prenom, mail FROM membre WHERE Prenom ='".$_SESSION['user']."'");
        $data_profil_connecte = $profil_connecte->fetch();
        echo $data_profil_connecte['Nom'].' '. $data_profil_connecte['Prenom'].' '. $data_profil_connecte['mail'];
    }else header('Location:index.php');

    
    ?>
</body>
</html>