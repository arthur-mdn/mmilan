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
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/teams.css" />
    <style>
        .table_container {
            width: 100%;
            height: 100%;
            display: flex;
            overflow-x: auto;
            margin-top: 1rem;
        }

        table {
            font-family: "Work Sans", sans-serif;
            margin-inline: 1rem;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        table tr:nth-child(even) {
            background-color: #dbecff;
        }

        table tr:nth-child(odd) {
            background-color: #fff;
        }

        table th {
            background-color: #f9d71c;
            color: black;

        }

        table td,
        td a {
            color: black;

        }
    </style>
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

    <section class="container">

        <h2 class="head_title primary" style="font-size: clamp(20px, 3vw, 40px);">Les Joueurs</h2>

        <?php
        $fetchPlayers = $conn2->prepare("SELECT * FROM players WHERE players.PlayerStatus = 'ok' AND players.PlayerRole NOT IN ('admin', 'staff')");
        $fetchPlayers->execute();
        $players = $fetchPlayers->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h4>Il y actuellement <?= count($players) ?> Joueurs/ses inscrit(e)s</h4>
        <div class="table_container">
            <table>
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Pseudo</th>
                        <th>Mail</th>
                        <th>Tél</th>
                        <th>Discord</th>
                        <th>Role</th>
                        <th>Promo</th>
                    </tr>
                </thead>

                <?php
                foreach ($players as $player) {
                    echo '<tr>';
                    echo '<td>' . $player['PlayerFirstname'] . '</td>';
                    echo '<td>' . $player['PlayerLastname'] . '</td>';
                    echo '<td>' . $player['PlayerUsername'] . '</td>';
                    echo '<td><a href="mailto:' . $player['PlayerEmail'] . '">' . $player['PlayerEmail'] . '</a></td>';
                    echo '<td><a href="tel:' . $player['PlayerTel'] . '">' . $player['PlayerTel'] . '</a></td>';
                    echo '<td>' . $player['PlayerDiscord'] . '</td>';
                    echo '<td>' . $player['PlayerRole'] . '</td>';
                    echo '<td>' . $player['PlayerProfil'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>


    </section>

    <!-- Script JS pour le carousel -->
    <script>
        const gap = 16;

        const carousel = document.getElementById("carousel"),
            content = document.getElementById("content"),
            next = document.getElementById("next"),
            prev = document.getElementById("prev");

        next.addEventListener("click", e => {
            carousel.scrollBy(width + gap, 0);
            if (carousel.scrollWidth !== 0) {
                prev.style.display = "flex";
            }
            if (content.scrollWidth - width - gap <= carousel.scrollLeft + width) {
                next.style.display = "none";
            }
        });
        prev.addEventListener("click", e => {
            carousel.scrollBy(-(width + gap), 0);
            if (carousel.scrollLeft - width - gap <= 0) {
                prev.style.display = "none";
            }
            if (!content.scrollWidth - width - gap <= carousel.scrollLeft + width) {
                next.style.display = "flex";
            }
        });

        let width = carousel.offsetWidth;
        window.addEventListener("resize", e => (width = carousel.offsetWidth));
    </script>
    <?php
    include_once './includes/footer.php';
    ?>
</body>




</html>