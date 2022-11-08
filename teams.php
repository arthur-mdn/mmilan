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
    <script src="js/main_script.js"></script>

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
    ?>

    <div class="content-container" style="height:100%;display: flex;flex-direction: column;justify-content: center; padding-top:100px;">
        <h3 style="    font-size: clamp(20px, 3vw, 40px);">Equipes</h3>

    </div>
    </div>


</body>

</html>