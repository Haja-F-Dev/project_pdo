<?php

// Fichier de connexion à la base de données
require_once '_connect.php';

// Connexion à la base de données
$pdo = new PDO(DSN, USER, PASSWORD);

// Requête pour récupérer tous les amis
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRIENDS</title>
</head>

<body>
    <h2>My friends</h2>
    <!-- AFFICHAGE DE LA LISTE D'AMIS -->
    <?php foreach ($friends as $friend) : ?>
        <h3>Friend <?= $friend['id'] ?> :</h3>
        <ul>
            <li>Firstname :<?= $friend['firstname'] ?></li>
            <li>Lastname :<?= $friend['lastname'] ?></li>
        </ul>
        <hr>
    <?php endforeach; ?>
    <br>
    <hr>

    <h2>Add new friend</h2>
    <form action="" method="post">
        <label for="firstname">Prénom :</label>
        <input type="text" id="firstname" name="firstname">
        <label for="lastname">Nom :</label>
        <input type="text" id="lastname" name="lastname">
        <button type="submit">Ajouter</button>
    </form>
    <hr>

    <?php
    // Verifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sécurisation des données saisies
        $firstname = htmlentities(trim($_POST['firstname']));
        $lastname = htmlentities(trim($_POST['lastname']));

        // Validation des données saisies
        if (empty($firstname)) {
            echo 'Veuillez saisir un prénom';
        } elseif (strlen($firstname) > 45) {
            echo 'Le prénom doit faire moins de 45 caractères';
        }

        if (empty($lastname)) {
            echo 'Veuillez saisir un nom';
        } elseif (strlen($lastname) > 45) {
            echo 'Le nom doit faire moins de 45 caractères';
        }

        // Requête SQL pour insérer un nouvel enregistrement dans la table friend
        $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);

        if ($statement->execute()) {
            // Redirection vers la page index
            header('Location: index.php');
        }
    }
    ?>
</body>

</html>