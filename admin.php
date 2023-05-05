<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Admin</title>
</head>
<body>
    <h2 class="container-fluid p-5 bg-primary text-white text-center">Inscription</h2>
    <?php
    include('common.php');
    $bdd = new PDO('mysql:host=localhost;dbname=cineps','root','');
    if(isset($_POST['new_membre'])){//Ajout nouveau membre si on a cliqué sur le bouton d'inscription
        $nom_de_famille = addslashes($_POST['name']);
        $prenom = addslashes($_POST['prenom']);
        $mail = addslashes($_POST['email']);
        $new_membre = $bdd->prepare("INSERT INTO `membre` (`Nom`, `Prenom`, `mail`, `mdp`) VALUES (?,?,?, 'Toto')");
        $new_membre->execute([$nom_de_famille, $prenom, $mail]);
   
    }
?>

    
        <form method="POST" id="signup-form" class="" action="">
            <div class="col">
                <input type="text" class="" name="name"  placeholder="Nom de famille"/>
            </div>
            <div class="col">
                <input type="text" class="" name="prenom" placeholder="Prenom"/>
            </div>
            <div class="col">
                <input type="email" class="" name="email" placeholder="email"/>
            </div>
            <div class="">
                <input type="submit" name="new_membre" class="form-submit submit" value="Inscription">
            </div>
        </form>
</br>
    
</br>
<h2 class="container-fluid p-5 bg-secondary text-white text-center"> Choix du proposeur pour la semaine souhaitée </h2>
<?php
//si il clique sur le bouton new_proposeur
if(isset($_POST['new_proposeur'])){
    $nom_proposeur = addslashes($_POST['user']);
    $date_proposeur = addslashes($_POST['date']);
    $date_to_insert = date("Y-m-d", strtotime($date_proposeur));
    $ajout_proposeur = $bdd->prepare("INSERT INTO `semaine` (`jour`, `proposeur`, `proposition_termine`, `theme`) VALUES (?,?,?,?)");
    $ajout_proposeur->execute([$date_to_insert, $nom_proposeur, '0', ""]);
}
$membres = $bdd->query('SELECT * FROM membre');
echo'<form method="post" action="">
        <label>Membres</label>
            <select class="text-dark" name="user">';
            
while($data = $membres->fetch()){ //Afficher un utlisateur dans le dropdown
    echo"<option class='text-dark' value=".$data['Prenom'].">". $data['Nom']." ".$data['Prenom']."</option>";
}
echo"<input type='date' name='date'>";
echo"</select>
<button type='submit' name='new_proposeur'>Soumettre</button>
</form>";
printNextproposeurs($id_current_semaine);

echo "<p class = 'text-center'><b>tokar <br/> pilou <br/> olivier <br/> fred <br/> renaud <br/> bebert <br/> marion <br/> royale <br/> grim</b></p>";

// Appel de la fonction pour récupérer les films proposés
$return_films_proposes = getFilmsProposes($id_current_semaine);


?>
<form method="post" action="">
    <table>
      <thead>
        <tr>
          <th></th>
          <th>Titre</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($return_films_proposes as $film) { ?>
          <tr>
            <td><input type="checkbox" name="films[]" value="<?php echo $film['film_id']; ?>"></td>
            <td><?php print_r($film); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <input type="submit" name="submit" value="Supprimer">
  </form>
  <?php
  if(isset($_POST['submit'])){
    if(!empty($_POST['films'])){
      foreach($_POST['films'] as $film_id){
        $delete_film = $bdd->prepare("DELETE FROM proposition WHERE film = ?");
        $delete_film->execute([$film_id]);
      }
    }
    }
?>
</body>
</html>