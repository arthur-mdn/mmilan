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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
    <script src="js/main_script.js"></script>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loader.css" />
    <link rel="stylesheet" href="uikit/style/ui-kit.css" />
    <link rel="stylesheet" href="css/program_css.css" />

</head>

<body>
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
    <section id='lan-infos' >
        <div class="el_1">
            <img src="Elements/others/TriangleJB.svg" alt="Triangle Blanc & Jaune" />
        </div>
        <div class="el_4">
            <img src="Elements/others/Vector.svg" alt="Chemin Vectoriel parcourant la page" />
        </div>
        <div class="el_2">
            <img src="Elements/others/01blue.svg" alt="Num 01 Bleu" />
        </div>
        <div id="1" class="infos-lan">
            <div class="img-lan">
                <img src="" alt="Photo de la borne d'arcade">
            </div>
            <div class="second-part"> 
                <div class="lan-information">
                    <h1 class="head_title primary titre-lan">MMI LAN</h1>  
                    <h2 class="date-lan">15/12/2022 à 08:00</h2> 
                </div>   
                <p class="txt-lan">La MMI LAN, c’est un événement qui rassemble les joueurs de jeux vidéos faisant parti de la promotion MMI à l’université de Toulon. Une première édition a eu lieu en 2020, en distanciel à cause du Covid-19. Cette année, nous voulons faire une LAN en présentiel, qui se déroulera à l’université de Toulon le jeudi 15 décembre. Les participants s’affronteront sur 6 jeux, et un jeu surprise est réservé pour la finale. On vous attend nombreux !</p>
            </div>
        </div>
    </section>


    <section id='gmaps'>
        <div class="el_3">
            <img  src="Elements/others/03blue.svg" alt="Num 02 Bleu" />
        </div>
        <div id="3" class="infos-position">
            <div class="gmaps">
            <iframe class="gmaps-effect" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1456.1052495917631!2d5.939343960184752!3d43.1211040238023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12c91b0a14bfe843%3A0x6bcfee881156660f!2sUniversit%C3%A9%20de%20Toulon%20Campus%20Porte%20d%E2%80%99Italie%20Toulon!5e0!3m2!1sfr!2sfr!4v1668156066411!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
    </section>


</body>

</html>