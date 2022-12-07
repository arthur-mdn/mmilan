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
    <title>Sponsors - Mmi Lan</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/sponsors.css" />


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
            <li><a href="#4">4</a></li>
            <div class="cursor"></div>
        </ul>
    </div>

    <h1 class="head_title primary sponsor-title">Nos sponsors</h1>
    <section id="1" class="sponsor-row">
        <div class="sponsor-img">
            <img src="Elements/sponsors/ekinsport_logo_color.svg" alt="Logo d'Ekinsport">
        </div>
        <div class="sponsor-txt">
            <p class="nom-sponsor">Ekinsport</p>
            <p class="sponsor-exp">Ils nous ont offert des T-Shirt pour pouvoir représenter le staff le jour de l'évènement.</p>
        </div>
        <div class="el_1">
            <img src="Elements/others/01blue.svg" alt="Numéro O1 Bleu">
        </div>
    </section>
    <section id="2" class="sponsor-row-reverse">
        <div class="sponsor-img">
            <img src="Elements/sponsors/goodfood_logo_color.svg" alt="Logo de GoodFood">
        </div>
        <div class="sponsor-txt">
            <p class="nom-sponsor">GoodFood</p>
            <p class="sponsor-exp-reverse">Ils nous ont offert des prix pour les gagnants de notre lan.</p>
        </div>
        <div class="el_1">
            <img src="Elements/others/02White.svg" alt="Numéro O2 Blanc">
        </div>
    </section>
    <section id="3" class="sponsor-row">
        <div class="sponsor-img">
            <img src="Elements/sponsors/podium_logo_color.svg" alt="Logo de Podium">
        </div>
        <div class="sponsor-txt">
            <p class="nom-sponsor">Podium</p>
            <p class="sponsor-exp">Ils nous ont offert les trophées personalisés pour les gagnants.</p>
        </div>
        <div class="el_1">
            <img src="Elements/others/03blue.svg" alt="Numéro O3 Bleu">
        </div>
    </section>
    <section id="4" class="sponsor-row-reverse">
        <div class="sponsor-img">
            <img src="Elements/sponsors/rct_logo_color.svg" alt="Logo du RCT">
        </div>
        <div class="sponsor-txt">
            <p class="nom-sponsor">RCT</p>
            <p class="sponsor-exp-reverse">Ils nous ont offert des prix pour les gagnants de notre lan.</p>
        </div>
        <div class="el_1">
            <img src="Elements/others/04White.svg" alt="Numéro O4 Blanc">
        </div>
    </section>

    <?php
    include("includes/footer.php");
    ?>
</body>

</html>