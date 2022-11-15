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
    <section class="teams_display_section">

        <h2 class="head_title primary" style="font-size: clamp(20px, 3vw, 40px);">Les Equipes</h2>

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
                            <div class="team_description"><p>' . $team['TeamDesc'] . '</p></div>
                        </div>
                        <div class="team_players">
                            <h4 class="team_players_title">Joueurs</h4>
                            <ul class="team_players_list">';
                $fetchPlayers = $conn2->prepare("SELECT * FROM players, appartient WHERE appartient.AppartientTeamId = ? AND appartient.AppartientPlayerId = players.PlayerId AND players.PlayerStatus = 'ok'");
                $fetchPlayers->bindValue(1, $team['TeamId']);
                $fetchPlayers->execute();
                $players = $fetchPlayers->fetchAll(PDO::FETCH_ASSOC);
                foreach ($players as $player) {
                    echo '<li class="team_player"> <div class="player_main_infos"><h4>' . $player['PlayerFirstname'] . ' ' . $player['PlayerLastname'] . ' (' . $player['PlayerUsername'] . ') </h4></div> <div class="player_contact"><b>mail : </b>' . $player['PlayerEmail'] . ', <b>discord :</b> ' . $player['PlayerDiscord']  . '</div> </li>';
                }
                echo '</ul>
                        </div>

                    </div>';
            }
        } else {
            echo '<p class="no_team">Aucune équipe n\'est disponible pour le moment.</p>';
        }



        ?>

    </section>













    <!-- Partie Joueurs Solo à remplir par Alan.T & Rayan.H  -->
    <!-- <section class="alone_players_display_section"> -->
    <!-- Éléments graphiques (pas encore responsive) -->
    <!--         <div class="graphics">
            <img src="./Elements/graphics/elements_equipe.svg" class="element_right" alt="Element graphique équipe droite">

            <img src="./Elements/graphics/elements_equipe.svg" class="element_left" alt="Element graphique équipe gauche">
        </div> -->
    <!-- Titre de la section-->
    <!--         <div class="title_section">
            <h2 class="head_title secondary" style="    font-size: clamp(20px, 3vw, 40px);">Les Participants Seuls
            </h2>
        </div> -->
    <!-- Texte de la section-->
    <!--         <div class="text_content">
            <p class="text">Vous n’avez pas d’équipe à l’heure actuelle ? Ne vous inquiétez pas, on se charge de tout !
                Il suffit juste de vous inscrire et vous aurez une équipe composée d’incroyables étudiants MMI lors du tournoi.

                <br><br>N’hésitez plus et inscrivez-vous !
            </p>
        </div> -->

    <!-- Bouton de la section-->
    <!--         <div class="btn_section">
            <button class="btn btnregister">S'inscrire</button>
        </div> -->
    <!-- Carousel (avec JS en bas de page)-->
    <!--         <div id="wrapper">
            <div id="carousel">
                <div id="content">
                   
                    <div class="players">
                        <img class="item" src="./Elements/avatars/players/p1.jpg" alt="img" />
                        <p class="textinfo">NOM PRÉNOM <svg class="icon_info" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="#0a1929" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12" stroke="white"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8" stroke="white"></line>
                            </svg></p>
                    </div>

                    

                </div>

    
                <button id="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" class="controllers" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M15.61 7.41L14.2 6l-6 6 6 6 1.41-1.41L11.03 12l4.58-4.59z" />
                    </svg>
                </button>
                <button id="next">
                    <svg xmlns="http://www.w3.org/2000/svg" class="controllers" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z" />
                        <path d="M10.02 6L8.61 7.41 13.19 12l-4.58 4.59L10.02 18l6-6-6-6z" />
                    </svg>
                </button>
            </div> -->

    <!-- </section> -->


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