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
    <title>Accueil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <script src="js/main_script.js"></script>

    <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loader.css" />
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

    <div style="background-image: url(Elements/backgrounds/bg_1.jpg);
    height:700px;
    background-size: cover;
    background-position: center;
    margin: 0 auto;
    width: 100%;
    max-width: inherit;
    ">
        <div class="content-container" style="height:100%;display: flex;flex-direction: column;justify-content: center;">
            <h3 style="    font-size: clamp(20px, 3vw, 40px);">MMI LAN</h3>
            <h3 style="    font-size: clamp(30px, 5vw, 60px);">Accueil</h3>
            <div>
                <p>Site en construction.</p>
                <p>Revenez plus tard !</p>
                <p>Vous pouvez quand même créer un compte et commencer à composer votre équipe !</p>

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

                <style>
                    #counter_status{
                        font-size: clamp(15px,5vw,30px);
                    }
                    #counters_container{
                        display: flex;
                        gap: 15px;
                    }
                    .counter_container{
                        background-color: rgba(0,0,0,0.7);
                        padding: 25px;
                        border-radius: 15px;
                        width: fit-content;
                    }
                    .precise_counter_container span{
                        font-size: clamp(45px,12vw,115px);
                    }
                    .precise_counter_container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }
                </style>
                <script>
                    var countDownDate = new Date("<?php echo date('M j, Y H:i:s', strtotime($settings['event_starting_date']) ) ;?>").getTime();
                    var countDownDateEnd = new Date("<?php echo date('M j, Y H:i:s', strtotime($settings['event_ending_date']) ) ;?>").getTime();

                    function update_counter(){
                        var now = new Date().getTime();

                        var distance = countDownDate - now;
                        var distanceEnd = countDownDateEnd - now;
                        if(distanceEnd < 0){
                            clearInterval(x);
                            document.getElementById("counter_status").innerHTML = "L'évènement est terminé. <br> Merci pour votre participation !";
                            document.getElementById("counters_container").style.display = "none";
                            return;
                        }

                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        if (distance < 0) {
                            document.getElementById("days_counter_container").style.display = "none";
                            document.getElementById("counter_status").innerHTML = "L'évènement a démarré depuis :";
                            document.getElementById("hours_counter").innerHTML = hours > -10 ? "0" + hours.toString().split("-").pop() : hours.toString().split("-").pop();
                            document.getElementById("minutes_counter").innerHTML = minutes > -10 ? "0" + minutes.toString().split("-").pop() : minutes.toString().split("-").pop();
                            document.getElementById("seconds_counter").innerHTML = seconds > -10 ? "0" + seconds.toString().split("-").pop() : seconds.toString().split("-").pop();
                        }else{
                            if(days <= 0){
                                document.getElementById("days_counter_container").style.display = "none";
                            }else{
                                document.getElementById("days_counter").innerHTML = days.toString();
                            }
                            document.getElementById("counter_status").innerHTML = "Début de l'évènement dans :";
                            document.getElementById("hours_counter").innerHTML = hours < 10 ? "0" + hours.toString().split("-").pop() : hours.toString();
                            document.getElementById("minutes_counter").innerHTML = minutes < 10 ? "0" + minutes.toString().split("-").pop() : minutes.toString();
                            document.getElementById("seconds_counter").innerHTML = seconds < 10 ? "0" + seconds.toString().split("-").pop() : seconds.toString();
                        }
                    }

                    var x = setInterval(function() {
                        update_counter();
                    }, 1000);

                    update_counter();


                </script>
            </div>
        </div>
    </div>

    <div class="content-container">
        <?php
        $query = $conn2->prepare("SELECT * 
									FROM games
									WHERE games.GameStatus != 'del'");
        $query->execute();
        $games = $query->fetchAll(PDO::FETCH_ASSOC); // get all games in bdd
        if (!empty($games)) {
            echo '<h2> Jeux </h2>';
            foreach ($games as $game) {
                echo '
                <div>
                <br>
                    <h4>' . $game['GameName'] . '</h4>
                    <p>' . $game['GameDescription'] . '</p>
                    <img src="' . $game['GamePicture'] . '" style="width: 50px;">
                </div>
                ';
            }
        }
        ?>
    </div>

</body>

</html>