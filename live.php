<style>
    .container {
        padding-top: 100px;
        width: min(100% - 2rem, 1300px);
        height: auto;
        margin: 0 auto;
    }

    #Classement {
        padding-block: 50px;
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        gap: 4rem;

    }

    .Classement_h1 {
        margin-bottom: 1rem;
        position: relative;
    }

    .Classement_Team {
        position: relative;
        display: flex;
        flex: 1 0 40%;
        flex-wrap: wrap;
        gap: 1rem;
        background-color: #FFFF;
        color: black;
        box-shadow: 0.35rem 0.35rem #f9d71c;
        border-radius: 5px;
        margin-left: 3rem;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease-in-out;
    }

    .Classement_Team:hover {
        transform: scale(1.025);
        box-shadow: 0.05rem 0.05rem #f9d71c;
    }

    .Team_Rank {
        position: absolute;
        top: -0.25rem;
        left: -0.15rem;
        transform: translate(-50%, -50%);
        color: #f9d71c;
        font-size: clamp(2rem, 6vw, 3rem);
        font-family: "Akira";
        text-shadow: 8px 8px rgba(0, 0, 0, 0.3);
    }

    .players,
    .player {
        color: black;
    }

    .players {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 1rem;
        align-items: center;
    }

    .Team_Name {
        color: black;
        font-weight: bold;
        font-size: 1.5rem;
        word-break: break-all;
    }
</style>
<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
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
?>
<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Classement - MMI LAN</title>
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/programCss.css" />
    <link rel="stylesheet" href="css/mediaCss.css" />

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



    <section id="twitch">
        <div class="el_1">
            <img src="Elements/others/TriangleJB.svg" alt="Triangle Blanc & Jaune" />
        </div>

        <div id="1" class="container">
            <div class="twitchtitle">
                <h1 class="head_title primary"> Live Twitch </h1>
            </div>

            <div class="sectiontwitch">

                <iframe src="https://player.twitch.tv/?channel=oximuss_&parent=mmilan.fr" frameborder="0" allowfullscreen="true" scrolling="no" class="twitchflux">
                </iframe>
                <iframe class="twitchchat" id="chat_embed" src="https://www.twitch.tv/embed/oximuss_/chat?parent=mmilan.fr" height="500" width="350">
                </iframe>
            </div>

        </div>
    </section>
    <div class="container">
        <div class="Classement-h1">
            <h1 class="head_title primary titre-classement">Classement</h1>
        </div>
        <section id="Classement">
            <?php


            $sql = "SELECT teams.TeamId, teams.TeamRank, teams.TeamName FROM `teams` ORDER BY teams.TeamRank DESC";
            $result = $conn2->prepare($sql);
            $result->execute();
            $teams = $result->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($teams); $i++) {
                echo "<a class='Classement_Team' href='./team?team=" . $teams[$i]['TeamId'] . "'>
          <div class='Team_Rank'>" . $teams[$i]['TeamRank'] . "</div>
          <div class='Team_Name'>" . $teams[$i]['TeamName'] . "</div>" .
                    "<div class='players'>";

                $sql = "SELECT * FROM `players`, `appartient` WHERE players.PlayerId = appartient.AppartientPlayerId AND appartient.AppartientTeamId = ?";
                $result = $conn2->prepare($sql);
                $result->bindValue(1, $teams[$i]['TeamId']);
                $result->execute();
                $players = $result->fetchAll(PDO::FETCH_ASSOC);

                for ($j = 0; $j < count($players); $j++) {
                    echo "<div class='player'>" . $players[$j]['PlayerUsername'] . "</div>";
                }
                echo "</div>";
                echo "</a>";
            }
            ?>
        </section>
    </div>

    <?php
    include_once './includes/footer.php';
    ?>
</body>

</html>