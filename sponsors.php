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
    <title>Page Sponsors - Mmi Lan</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/sponsors.css" />


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

    <section id="1" class="sponsors-section">
        <div class="el_1">
            <img src="Elements/others/01White.svg" alt="Num 01White">
        </div>
        <h1 class="head_title secondary sponsor-title">Sponsors</h1>
        <div class="sponsor-row">
            <div class="sponsor-vignet">
                <img src="Elements/sponsors/Logo_Ekinsport.svg" alt="Logo d'Ekinsport" class="ekinsport">
                <p class="logo-txt">EKINSPORT</p>
            </div>
            <div class="sponsor-vignet">
                <img src="Elements/sponsors/Logo_GoodFood.svg" alt="Logo de GoodFood" class="goodfood">
                <p class="logo-txt">GOODFOOD</p>
            </div>
        </div>
        <div class="sponsor-row">
            <div class="sponsor-vignet">
                <img src="Elements/sponsors/Logo_podium.svg" alt="Logo de Podium" class="podium">
                <p class="logo-txt">PODIUM</p>
            </div>
            <div class="sponsor-vignet">
                <img src="Elements/sponsors/Logo_RCT.svg" alt="Logo du RCT" class="rct">
                <p class="logo-txt">RCT</p>
            </div>
        </div>
    </section>

    <section id="2" class="reseaux-social">
        <div class="el_2">
            <img src="Elements/others/02Blue.svg" alt="Num 02 Blue">
        </div>
        <h1 class="head_title primary rs-title">Reseaux sociaux</h1>
        <div class="rs-row">
            <div class="rs-vignet">
                <p class="logo-title">Instragram</p>
                <div class="rs-content">
                    <img src="Elements/others/Logo_Instagram_jaune.png" alt="Logo Instagram" class="rs-insta">
                    <a href="https://instagram.com/mmi_lan2022">
                        <p class="id-rs">@MMI_lan2022</p>
                    </a>
                </div>
            </div>
            <div class="rs-vignet">
                <p class="logo-title">Youtube</p>
                <div class="rs-content">
                    <img src="Elements/others/Logo_YT_jaune.png" alt="Logo Youtube" class="rs-youtube">
                    <a href="https://youtube.com/">
                        <p class="id-rs">@MMI_lan2022</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php
    include("includes/footer.php");
    ?>

</body>

</html>