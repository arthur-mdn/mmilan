<?php

/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 *
 * Contributors :
 * - Mathis Lambert
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
    <title>Accueil | MMI LAN</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/program-css.css">
    <link rel="stylesheet" href="css/accueil.css">

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

    <div class="landing-page">
        <div class="overlay-blur">
            <div class="blur"></div>
            <img src="./Elements/backgrounds/bg_1.jpg" alt="">
        </div>
        <div class="content-container">
            <div class="counter_container">
                <span id="counter_status"></span>
                <div id="counters_container">
                    <div class="precise_counter_container" id="days_counter_container">
                        <span id="days_counter">00</span>
                        <p>Jours</p>
                    </div>
                    <div class="precise_counter_container">
                        <span>&nbsp;</span>
                    </div>
                    <div class="precise_counter_container">
                        <span id="hours_counter">00</span>
                        <p>Heures</p>
                    </div>
                    <div class="precise_counter_container">
                        <span>:</span>
                    </div>
                    <div class="precise_counter_container">
                        <span id="minutes_counter">00</span>
                        <p>Minutes</p>
                    </div>
                    <div class="precise_counter_container">
                        <span>:</span>
                    </div>
                    <div class="precise_counter_container">
                        <span id="seconds_counter">00</span>
                        <p>Secondes</p>
                    </div>
                </div>
            </div>
            <p class="explicationLan">La MMI LAN arrive bientôt, ne la manquez pas ! Nous vous attendons très nombreux lors de cet événement incroyable, organisé par les étudiants de 2ème année de la promotion MMI à l’université de Toulon.</p>
            <form action="#2">
                <input type="submit" class="btn btn__primary" value="Voir plus">
            </form>
        </div>
    </div>
    </div>
    <section class="infos-lan container">
        <div class="el_1">
            <img src="Elements/others/01Blue.svg" alt="Num 01 Blue" />
        </div>
        <div class="written-infos">
            <h1 class=" head_title primary infos-title">Informations </h1>
            <p class="infos-txt">Le premier tournoi de jeux vidéos fait par des étudiants pour des étudiants arrive bientôt ! Cet événement rassemble toutes les promotions MMI de l’université de Toulon passionnés de jeux vidéos. Tous les étudiants de 2ème année se sont regroupés pour vous offrir une LAN incroyable, qui se déroulera à l’université de Toulon le jeudi 15 décembre. Les participants s’affronteront sur 5 jeux, et un jeu surprise est réservé pour la finale. On vous attend nombreux !</p>
            <div class="button-section">
                <form action="login.php">
                    <input type="submit" class="btn btn__primary" value="Se connecter" />
                </form>
                <form action="register.php">
                    <input type="submit" class="btn btn__primary" value="S'inscrire" />
                </form>
            </div>

        </div>
        <div class="photos-lan">
            <img src="Elements/others/UnivTln.jpg" class="univ-tln" alt="Photo de l'iut de toulon">
        </div>
    </section>
    <section class="presentationJeux" id="games">
        <h1 class="head_title secondary titre_jeux">Les jeux :</h1>
        <div class="el_4">
            <img src="Elements/others/02White.svg" alt="Triangle Blanc & Jaune" />
        </div>
        <div class="jeux">
            <div class="jeux__item" data-game="mystere">

                <img src="Elements/programme/JeuMystereVisu.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>JEU MYSTERE</h2>
                        <p>C'est le jeu mystère de notre lan que personne ne connait, il sera dévoillé environ 48 à 72h avant le début de lan! Alors à très bientôt!</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="fallguys">

                <img src="Elements/programme/FallGuysVisu.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>FALL GUYS</h2>
                        <p>Venez incarner un petit bonhomme coloré dans un décor psychédélique ! Évitez les obstacles et tapez votre meilleur sprint afin de franchir la ligne d’arrivée. </p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="rocketleague">

                <img src="Elements/programme/RocketLeagueVisu.png" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>ROCKET LEAGUE</h2>
                        <p>Le jeu qui mélange football et sport motorisé, affrontez-vous dans une arène enflammée ! Marquez le maximum de buts pour mener votre équipe à la victoire !</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="overwatch">

                <img src="Elements/programme/OverWatchVisu.png" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>OVERWATCH 2</h2>
                        <p> Ce jeu d’action et de combat pourra faire basculer le destin de chaque équipe. Choisissez vos héros et affrontez vos adversaires dans des combats sans pitié !</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="cityguesser">

                <img src="Elements/programme/CityGuesserVisu.png" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>CITY GUESSER</h2>
                        <p> Atterrissez dans un champ de blé, une route abandonnée ou au milieu d’une grande ville, explorez et devinez à quel endroit vous êtes dans le monde entier !</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="switchsports">

                <img src="Elements/programme/SwitchSportVisu.png" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>SWITCH SPORTS</h2>
                        <p>Frappez, lancez et smashez dans Nintendo Switch Sport ! Affrontez vous sur un terrain de volley, une piste de bowling ou un ring de chambara.</p>
                    </div>
                </div>

            </div>



        </div>
        <div class="carouselSection">
            <div class="carousel">
                <ul class="carousel__list">

                    <li class="carousel__item" data-pos="-3" data-game="overwatch"></li>
                    <li class="carousel__item" data-pos="-2" data-game="fallguys"></li>
                    <li class="carousel__item" data-pos="-1" data-game="rocketleague"></li>
                    <li class="carousel__item" data-pos="0" data-game="mmilan"></li>
                    <li class="carousel__item" data-pos="1" data-game="cityguesser"></li>
                    <li class="carousel__item" data-pos="2" data-game="switchsports"></li>
                    <li class="carousel__item" data-pos="3" data-game="mystere"></li>

                </ul>
            </div>

            <div class="game">

                <img src="Elements/images/left_arrow.png" class="left_arrow">
                <div class="gametext">
                <div class="overwatch">
                        <h2>OVERWATCH 2</h2>
                        <p>Ce jeu d’action et de combat pourra faire basculer le destin de chaque équipe. Choisissez vos héros et affrontez vos adversaires dans des combats sans pitié !</p>
                    </div>

                    <div class="rocketleague">
                        <h2>ROCKET LEAGUE</h2>
                        <p>Le jeu qui mélange football et sport motorisé, affrontez-vous dans une arène enflammée ! Marquez le maximum de buts pour mener votre équipe à la victoire </p>
                    </div>

                    <div class="cityguesser">
                        <h2>CITY GUESSER</h2>
                        <p>Atterrissez dans un champ de blé, une route abandonnée ou au milieu d’une grande ville, explorez et devinez à quel endroit vous êtes dans le monde entier !</p>
                    </div>

                    <div class="fallguys">
                        <h2>FALL GUYS</h2>
                        <p>Venez incarner un petit bonhomme coloré dans un décor psychédélique ! Évitez les obstacles et tapez votre meilleur sprint afin de franchir la ligne d’arrivée.</p>
                    </div>

                    <div class="mmilan">
                        <h2>NOS JEUX</h2>
                        <p>Swipe à droite ou à gauche pour voir les différents jeux de la lan !</p>
                    </div>

                    <div class="switchsports">
                        <h2>SWITCH SPORTS</h2>
                        <p>Frappez, lancez et smashez dans Nintendo Switch Sport ! Affrontez vous sur un terrain de  volley, une piste de bowling ou un ring de chambara.</p>
                    </div>

                    <div class="mystere">
                        <h2>JEU MYSTERE</h2>
                        <p>C'est le jeu mystère de notre lan que personne ne connait, il sera dévoillé environ 48 à 72h avant le début de lan! Alors à très bientôt!</p>
                    </div>

                </div>

                </div>
                <img src="Elements/images/right_arrow.png" class="right_arrow">

            </div>
        </div>
    </section>






    <script href="js/accueil.js"></script>
    <script src="js/program_carousel.js"></script>
    <script>
        let countDownDate = new Date("<?= date('M j, Y H:i:s', strtotime($settings['event_starting_date'])); ?>").getTime();
        let countDownDateEnd = new Date("<?= date('M j, Y H:i:s', strtotime($settings['event_ending_date'])); ?>").getTime();

        const update_counter = () => {
            let now = new Date().getTime();
            let distance = countDownDate - now;
            let distanceEnd = countDownDateEnd - now;

            if (distanceEnd < 0) {
                clearInterval(interval);
                document.getElementById("counter_status").innerHTML = "L'évènement est terminé. <br> Merci pour votre participation !";
                document.getElementById("counters_container").style.display = "none";
                return;
            }

            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance < 0) {
                document.getElementById("days_counter_container").style.display = "none";
                document.getElementById("counter_status").innerHTML = "L'évènement a démarré depuis :";
                document.getElementById("hours_counter").innerHTML = hours > -10 ? "0" + hours.toString().split("-").pop() : hours.toString().split("-").pop();
                document.getElementById("minutes_counter").innerHTML = minutes > -10 ? "0" + minutes.toString().split("-").pop() : minutes.toString().split("-").pop();
                document.getElementById("seconds_counter").innerHTML = seconds > -10 ? "0" + seconds.toString().split("-").pop() : seconds.toString().split("-").pop();
            } else {
                if (days <= 0) {
                    document.getElementById("days_counter_container").style.display = "none";
                } else {
                    document.getElementById("days_counter").innerHTML = days.toString();
                }
                document.getElementById("counter_status").innerHTML = "Début de l'évènement dans :";
                document.getElementById("hours_counter").innerHTML = hours < 10 ? "0" + hours.toString().split("-").pop() : hours.toString();
                document.getElementById("minutes_counter").innerHTML = minutes < 10 ? "0" + minutes.toString().split("-").pop() : minutes.toString();
                document.getElementById("seconds_counter").innerHTML = seconds < 10 ? "0" + seconds.toString().split("-").pop() : seconds.toString();
            }
        }

        const interval = setInterval(update_counter, 1000);

        update_counter();
    </script>


    <?php include './includes/footer.php'; ?>

</body>

</html>