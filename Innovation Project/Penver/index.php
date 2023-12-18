<?php
# Initialize the session
session_start();

require_once "database/config.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Accueil - Penver</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="styles/index.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    </head>
    <body>
        <!-- Barre de navigation horizontale -->
        <header class="header">
            <div class="logo">
                <a href="index.php"><img class="logo" src="images/logo.png"></a>
            </div>
            <ul>
                <?php 
                # Ne pas afficher toutes les options si l'on n'est pas connecté
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
                    echo '<li><i class="bi-person-circle" style="font-size: 18px; font-style: normal;"> <a href="login.php">Se connecter...</a></i></li>';
                } 
                else
                {
                    echo '<li><span style="font-size: 18px; font-style: normal;">
                    <img src="images/avatar.png" width="30px;" height="30px;"> <a href="user/' . $_SESSION["Pseudo"] . '.php">' . $_SESSION["Pseudo"] . '</a></span></li>';
                    echo '<li><i class="bi-door-open-fill" style="font-size: 18px; font-style: normal;"> <a href="logout.php">Se déconnecter...</a></i></li>';
                    echo '<li><i class="bi-bell-fill" style="font-size: 18px; font-style: normal;"> Notifications</i></li>';
                    echo '<li><i class="bi-chat-left-dots-fill" style="font-size: 18px; font-style: normal;"> Messages</i></li>';
                }
                ?>
                <li><i class="bi bi-search" style="font-size: 18px; font-style: normal;"> Recherche</i></li>
            </ul>
        </header>
        <!-- Barre de navigation vertical -->
        <nav class="sidebar">
            <ul>
                <li><a href="index.php"><i class="bi-house-door-fill" style="font-size: 18px; font-style: normal;"> ACCUEIL</i></a></li>
                <li><a href="explore.php"><i class="bi-arrow-up-right-square-fill" style="font-size: 18px; font-style: normal;"> EXPLORER</i></a></li>
                <li><a href="trends.php"><i class="bi-bar-chart-line-fill" style="font-size: 18px; font-style: normal;"> TENDANCES</i></a></li>
                <?php
                # Pareil pour la barre de navigation
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
                    echo '';
                }
                else
                {
                    echo '<li><a href="settings.php"><i class="bi-gear-fill" style="font-size: 18px; font-style: normal;"> PARAMÈTRES</i></a></li>';
                    echo '<li><a href="more.php"><i class="bi-three-dots" style="font-size: 18px; font-style: normal;"> PLUS</i></a></li>';
                }
                ?>
            </ul>
        </nav>
        <!-- Barre de navigation vertical -->
        <nav class="contacts">
            <ul>
                <li><i class="bi-person-circle" style="font-size: 18px; font-style: normal;"> Utilisateur 1</i></li>
                <li><i class="bi-person-circle" style="font-size: 18px; font-style: normal;"> Utilisateur 2</i></li>
                <li><i class="bi-person-circle" style="font-size: 18px; font-style: normal;"> Utilisateur 3</i></li>
                <li><i class="bi-person-circle" style="font-size: 18px; font-style: normal;"> Utilisateur 4</i></li>
                <li><i class="bi-person-circle" style="font-size: 18px; font-style: normal;"> Utilisateur 5</i></li>
            </ul>
        </nav>
        <!-- Posts -->
        <?php
            // Récupération des messages depuis la base de données
            $query = "SELECT message, id_utilisateur, image, PP, Pseudo FROM feed_global NATURAL JOIN user";
            $result = mysqli_query($link, $query);

            if ($result) {
                // Affichage des messages
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<article class="posts">';
                    echo '<span style="font-weight: bold"><img src="data:image/jpeg;base64,' . base64_encode( $row['PP'] ) . '" width="32px" height="32px"> ' . $row['Pseudo'] . '</span>';
                    echo '<br><br>' . $row['message'];
                    echo '<br><img src="data:image/jpeg;base64,' . base64_encode( $row['image'] ) . '">';
                    echo '</article>';
                }
                // Libération des résultats
                mysqli_free_result($result);
            } else {
                echo "Erreur : Impossible de récupérer les messages.";
            }
            
            // Fermeture de la connexion à la base de données
            mysqli_close($link);
        ?>
    </body>
</html>