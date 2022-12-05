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
    <title>Les équipes | MMILAN</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/teams.css" />
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

    <div class="frise_container">
        <div class="frise">
            <img src="./Elements/graphics/frise.svg">
        </div>
    </div>
    <?php
    require('menu.php'); // afficher le menu en fonction de connecté ou pas.
    ?>



    <!-- Partie Équipe a remplir par Roman.S & Axel.G -->
    <div class="container">

        <h2 class="head_title primary" style="font-size: clamp(20px, 3vw, 40px);">Les Équipes</h2>


        <section class="teams_container">
            <?php
            $fetchTeams = $conn2->prepare("SELECT * FROM teams WHERE TeamStatus = 'ok'");
            $fetchTeams->execute();
            $teams = $fetchTeams->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($teams)) {
                foreach ($teams as $team) {
                    echo '<div class="team_container">
                            <div class="team_logo">
                                <img src="' . $team['TeamLogo'] . '" alt="Logo de l\'équipe ' . $team['TeamName'] . '">
                            </div>
                            <div class="team_infos">
                                <h3 class="team_name">' . $team['TeamName'] . '</h3>
                            </div>
                            <a href="team?team=' . $team['TeamId'] . '" class="team_link btn btn__secondary">Découvrir l\'équipe</a>
                        </div>';
                }
            } else {
                echo '<p class="no_team">Aucune équipe n\'est disponible pour le moment.</p>';
            }



            ?>

        </section>
    </div>
    <?php
    include_once './includes/footer.php';
    ?>
</body>




</html>