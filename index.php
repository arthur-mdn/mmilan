<?php

/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 *
 * Contributors :
 * -
 *
 */
session_start();
define('MyConst', TRUE);
require('app/config.php');

if (isset($_SESSION["PlayerId"])) {
    $query = $conn2->prepare("SELECT * 
									FROM players
									WHERE players.PlayerStatus = 'ok'
									and players.PlayerId = ?");
    $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        header("Location: logout.php?blocked=true");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Accueil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loader.css" />
</head>

<body>
    <div class="loader_container" id="loader_container">
        <div class="a">
            <div></div>
            <div></div>
        </div>
        <p>Un instant ...</p>
        <script>
            function active_loader() {
                document.getElementById('loader_container').style.display = 'flex';
            }
        </script>
    </div>
    <?php
    require('menu.php'); // afficher le menu en fonction de connecté ou pas.
    // require('navbar.php'); afficher la navbar en fonction de connecté ou pas.
    ?>

    <style>
        body {
            padding-top: 64px;
        }

        .bt-lg {
            border-top: 1px solid #d3d3d37d;
        }
    </style>


    <div style="background-image: url(Elements/backgrounds/background03.jpg);
    height:400px;
    background-size: cover;
    background-position: center;
    margin: 0 auto;
    width: 100%;
    max-width: inherit;
    ">
        <div class="content-container" style="height:100%;display: flex;flex-direction: column;justify-content: center;">
            <h3 style="    font-size: clamp(20px, 3vw, 40px);">MMI LAN</h3>
            <h3 style="    font-size: clamp(30px, 5vw, 60px);">Accueil</h3>
            <div>
                <p>Site en construction.</p>
                <p>Revenez plus tard !</p>
                <p>Vous pouvez quand même créez un compte et commencer à composer votre équipe !</p>
            </div>
        </div>
    </div>

    <div class="content-container">
        <?php
        $query = $conn2->prepare("SELECT * 
									FROM games
									WHERE games.GameStatus != 'del'");
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_ASSOC); // get all games in bdd
        if (!empty($games)) {
            echo '<h2> Jeux </h2>';
            foreach ($games as $game) {
                echo '
                <div>
                <br>
                    <h4>' . $game['GameName'] . '</h4>
                    <p>' . $game['GameDescription'] . '</p>
                    <img src="' . $game['GamePicture'] . '" style="width: 50px;">
                </div>
                ';
            }
        }
        ?>
    </div>

</body>

</html>