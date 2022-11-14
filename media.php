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
    <title>Médias</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/media-css.css" />


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
            <li><a href="#1" class="active">1</a></li>
            <li><a href="#2">2</a></li>
            <li><a href="#3">3</a></li>
            <div class="cursor"></div>
        </ul>
        <script>
            const cursor = document.querySelector(".cursor");
            const links_viewer = document.querySelectorAll(
                ".section-viewer ul li a"
            );
            const sectionWidth = links_viewer[0].getBoundingClientRect().height;

            links_viewer.forEach((link, index) => {
                console.log(index, sectionWidth);
                link.addEventListener("click", () => {
                    links_viewer.forEach((link) => {
                        link.classList.remove("active");
                    });
                    link.classList.add("active");
                    cursor.style.top = index * sectionWidth + "px";
                });
            });
        </script>
    </div>


    <section id="twitch">
        <div class="el_1">
            <img src="Elements/others/TriangleJB.svg" alt="Triangle Blanc & Jaune" />
        </div>

        <div class="twitchtitle">
            <h1 class="head_title primary"> Live Twitch </h1>
        </div>
        <div id="1" class="sectiontwitch">
            <iframe src="https://player.twitch.tv/?channel=oximuss_&parent=mmilan.fr" frameborder="0" allowfullscreen="true" scrolling="no" class="twitchflux">
            </iframe>
            <iframe class="twitchchat" id="chat_embed" src="https://www.twitch.tv/embed/oximuss_/chat?parent=mmilan.fr" height="500" width="350">
            </iframe>
        </div>
    </section>


    <section id="reseauxSocial">
        <div class="el_2">
            <img id="2" src="Elements/others/01blue.svg" alt="Num01 Bleu" />
        </div>
        <div class="rstitle">
            <h1 class="head_title primary">Reseaux sociaux</h1>
        </div>
        <div class="informations">
            <div class="infoslan">
                <img class="logolan" src="Elements/others/Logo_blanc.png" />
                <h1 class="landate">XX/XX/XXXX à XX:XX </h1>
            </div>
            <div class="rssociaux">
                <div class="youtubers">
                    <div class="center">
                        <img src="Elements/others/Logo_YT_jaune.png" class="logoyt"></img>
                    </div>
                    <h2 class="textcenter logoyttxt">Youtube</h2>
                </div>
                <div class="instagramrs">
                    <div class="center">
                        <img src="Elements/others/Logo_Instagram_jaune.png" class="logoinsta"></img>
                    </div>
                    <h2 class="textcenter logoinstatxt">MMi_lan2022</h2>
                </div>
            </div>
        </div>
    </section>


    <section id="sponsorSection">
        <div class="el_3">
            <img src="Elements/others/02White.svg" alt="Num02 Blanc" />
        </div>
        <div id="3" class="sponsor">
            <div class="sponsortitle">
                <h1 class="head_title secondary"> Sponsor de notre lan</h1>
            </div>

            <div class="sponsorrow1">
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
            </div>

            <div class="sponsorrow2">
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>