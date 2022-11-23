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
    <title>Programme - Mmi Lan</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/program-css.css" />

</head>

<body>
    <div class="frise_container">
        <div class="frise">
            <img src="Elements/others/Vector.svg" />
        </div>
    </div>
    <?php
    include 'menu.php';
    ?>
    <!-- SECTION VIEWER -->
    <div class="section-viewer">
        <ul>
            <li><a href="#lan-infos" class="active">1</a></li>
            <li><a href="#games">2</a></li>
            <li><a href="#gmaps">3</a></li>
            <div class="cursor"></div>
        </ul>
    </div>
    <section id='lan-infos'>
        <div class="el_1">
            <img src="Elements/others/TriangleJB.svg" alt="Triangle Blanc & Jaune" />
        </div>
        <div class="el_2">
            <img src="Elements/others/01blue.svg" alt="Num 01 Bleu" />
        </div>

        <div class="container" style="min-height: 100vh;display: flex;">
            <div id="1" class="infos-lan">
                <div class="img-lan">
                    <img class="imgarcade" src="Elements/others/arcade.jpg" alt="Photo de la borne d'arcade">
                </div>
                <div class="second-part">
                    <div class="lan-information">
                        <h1 class="head_title primary titre-lan">MMI LAN</h1>
                        <h2 class="date-lan">15/12/2022 à 08:00</h2>
                    </div>
                    <div class="lan-explication">
                        <p class="txt-lan">La MMI LAN, c’est un événement qui rassemble les joueurs de jeux vidéos faisant parti de la promotion MMI à l’université de Toulon. Une première édition a eu lieu en 2020, en distanciel à cause du Covid-19. Cette année, nous voulons faire une LAN en présentiel, qui se déroulera à l’université de Toulon le jeudi 15 décembre. Les participants s’affronteront sur 6 jeux, et un jeu surprise est réservé pour la finale. On vous attend nombreux !</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="presentationJeux" id="games">
        <h1 class="head_title secondary titre_jeux">Les jeux :</h1>
        <div class="el_4">
            <img src="Elements/others/02White.svg" alt="Triangle Blanc & Jaune" />
        </div>
        <div class="jeux">
            <div class="jeux__item" data-game="mystere">

                <img src="Elements/images/Mystere.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>JEU MYSTERE</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="fallguys">

                <img src="Elements/images/fg2.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>FALL GUYS</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="rocketleague">

                <img src="Elements/images/rl2.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>ROCKET LEAGUE</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="overwatch">

                <img src="Elements/images/ow2.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>OVERWATCH 2</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="cityguesser">

                <img src="Elements/images/cityguesser.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>CITY GUESSER</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>
                </div>

            </div>

            <div class="jeux__item" data-game="switchsports">

                <img src="Elements/images/sp2.jpg" alt="">

                <div class="overlay">
                    <div class="text-content">
                        <h2>SWITCH SPORTS</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
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
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>

                    <div class="rocketleague">
                        <h2>ROCKET LEAGUE</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>

                    <div class="cityguesser">
                        <h2>CITY GUESSER</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>

                    <div class="fallguys">
                        <h2>FALL GUYS</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>

                    <div class="mmilan">
                        <h2>NOS JEUX</h2>
                        <p>Swipe à droite ou à gauche pour voir les différents jeux de la lan !</p>
                    </div>

                    <div class="switchsports">
                        <h2>SWITCH SPORTS</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>

                    <div class="mystere">
                        <h2>JEU MYSTERE</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            ugiat, sed. Corporis nulla dignissimos quo persm.</p>
                    </div>

                </div>
                <img src="Elements/images/right_arrow.png" class="right_arrow">

            </div>
        </div>
    </section>


    <section id='gmaps'>
        <div class="el_3">
            <img src="Elements/others/03blue.svg" alt="Num 02 Bleu" />
        </div>
        <div class="container">
            <div id="3" class="infos-position">
                <div class="gmaps">
                    <iframe class="gmaps-effect" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1456.1052495917631!2d5.939343960184752!3d43.1211040238023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12c91b0a14bfe843%3A0x6bcfee881156660f!2sUniversit%C3%A9%20de%20Toulon%20Campus%20Porte%20d%E2%80%99Italie%20Toulon!5e0!3m2!1sfr!2sfr!4v1668156066411!5m2!1sfr!2sfr" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="position">
                    <div class="lan-position">
                        <h1 class="head_title primary titre-position">Ou sommes-nous?</h1>
                    </div>
                    <p class="positions-info">Université de Toulon</p>
                    <p class="positions-info">Campus Porte d'Italie</p>
                    <p class="positions-info">Bâtiment Coudon Salle CO315</p>
                    <p class="positions-info">Bâtiment Faron Salle FA110</p>

                </div>
            </div>
        </div>
    </section>

    <?php
    include './includes/footer.php';
    ?>

    <script src="js/program_carousel.js"></script>
</body>

</html>