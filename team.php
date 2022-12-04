<?php
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

if (isset($_GET['team'])) {
    $query = $conn2->prepare("SELECT * 
                                    FROM teams
                                    WHERE teams.TeamId = ?");
    $query->bindValue(1, htmlspecialchars($_GET['team'], ENT_QUOTES, 'UTF-8'));
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        header("Location: teams");
        exit();
    }
} else {
    header("Location: teams");
    exit();
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

    <section class="landing-team">
        <div class="container">
            <a href="teams" class="no-style back_teams">
                Retour aux équipes
            </a>
            <div class="team">
                <div class="team_name">
                    <h1><?= $result[0]['TeamName']; ?></h1>
                </div>
                <div class="team_description">
                    <p><?= $result[0]['TeamDesc']; ?></p>
                </div>

            </div>
        </div>
        <div class="team_logo">
            <img src="<?= $result[0]['TeamLogo']; ?>" alt="Logo de l'équipe">
        </div>
    </section>

    <!-- Partie Équipe a remplir par Roman.S & Axel.G -->
    <?php
    include_once './includes/footer.php';
    ?>
</body>




</html>