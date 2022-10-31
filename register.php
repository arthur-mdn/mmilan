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
?>
<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Créer un compte</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
    <link rel="stylesheet" href="css/login_style.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loader.css" />
</head>

<body class="body-panel">
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
    <br>
    <br>
    <br>

    <?php
    $redirect_join = (isset($_GET['JoinId']) and isset($_GET['JoinToken']));
    define('MyConst', TRUE);
    require('app/config.php');
    session_start();
    if (isset($_SESSION["PlayerId"])) {
        header('location: index.php');
        exit();
    }
    $are_all_set = isset($_POST['NomUtilisateur'], $_POST['PrenomUtilisateur'], $_POST['MailUtilisateur'], $_POST['TelUtilisateur'], $_POST['MdpUtilisateur'], $_POST['DiscordUtilisateur'], $_POST['ProfilUtilisateur'], $_POST['UsernameUtilisateur']);
    $are_not_empty = (!empty($_POST['NomUtilisateur']) &&  !empty($_POST['PrenomUtilisateur']) &&  !empty($_POST['MailUtilisateur']) &&  !empty($_POST['TelUtilisateur']) &&  !empty($_POST['MdpUtilisateur']) &&  !empty($_POST['DiscordUtilisateur']) &&  !empty($_POST['ProfilUtilisateur']) &&  !empty($_POST['UsernameUtilisateur']));

    if ($are_all_set && $are_not_empty) {
        $query = $conn2->prepare("SELECT count(*) as NombreUtilisateurs
                        FROM players 
                        WHERE (players.PlayerEmail = ? or players.PlayerTel = ?) ");
        $query->bindValue(1, $_POST['MailUtilisateur']);
        $query->bindValue(2, $_POST['TelUtilisateur']);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (isset($result[0]) and $result[0]['NombreUtilisateurs'] == 0) {
            $result = $result[0];
            $query = "SELECT max(PlayerId) + 1 as NewPlayerId
        FROM players";
            $result2 = $conn2->query($query)->fetch(); // look for the highest number of PlayerId and add 1. ==> Home-made Auto-Increment

            // check if game selected exists
            $check_games = $conn2->prepare("SELECT count(*) as NombreJeux
                        FROM games 
                        WHERE games.GameId = ? ");
            $check_games->bindValue(1, $_POST['FavGameUtilisateur']);
            $check_games->execute();
            $result3 = $check_games->fetchAll(PDO::FETCH_ASSOC);

            if ($result3 === 1) {
                // if there is no player in the database, the first player will have the id 1
                if (!isset($result2['NewPlayerId'])) {
                    $result2['NewPlayerId'] = 1;
                }

                // check if the profile is allowed
                $profile_allowed = ['mmi1', 'mmi2', 'enseignant', 'other'];
                if (!in_array($_POST['ProfilUtilisateur'], $profile_allowed)) {
                    $_POST['ProfilUtilisateur'] = 'other';
                }

                // Log account creation attempt
                $logconn = $conn2->prepare("INSERT INTO logs (LogMsg, LogUserMail) 
                    VALUES (?, ?)");
                $logconn->bindValue(1, "Account creation : " . $_POST['MailUtilisateur'] . "successfully created its account");
                $logconn->bindValue(2, $_POST['MailUtilisateur']);
                $logconn->execute();

                // create the account - insert the player in the database
                $query = $conn2->prepare("INSERT INTO players (PlayerId, PlayerLastname, PlayerFirstname, PlayerEmail, PlayerTel, PlayerPassword, PlayerCreation_date, PlayerDiscord, PlayerProfil,PlayerUsername, PlayerFavGameId) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");
                $query->bindValue(1, $result2['NewPlayerId']);
                $query->bindValue(2, htmlspecialchars($_POST['NomUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(3, htmlspecialchars($_POST['PrenomUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(4, htmlspecialchars($_POST['MailUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(5, htmlspecialchars($_POST['TelUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(6, password_hash($_POST['MdpUtilisateur'], PASSWORD_DEFAULT));
                $query->bindValue(7, date('Y-m-d H:i:s'));
                $query->bindValue(8, htmlspecialchars($_POST['DiscordUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(9, htmlspecialchars($_POST['ProfilUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(10, htmlspecialchars($_POST['UsernameUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(11, htmlspecialchars($_POST['FavGameUtilisateur'], ENT_QUOTES, 'UTF-8'));
                $query->execute(); // create user
                $result = $query->fetchAll(PDO::FETCH_ASSOC);

                // new session for the new player
                $_SESSION["PlayerId"] = $result2['NewPlayerId'];
                header("location: index.php?msg=accountCreated");
            } else {
                // if the game selected does not exist, log hack attempt
                $logHack = $conn2->prepare("INSERT INTO logs (LogMsg, LogUserMail) 
                    VALUES (?, ?)");
                $logHack->bindValue(1, "Hack attempt : " . $_POST['FavGameUtilisateur'] . " is not a valid game");
                $logHack->bindValue(2, $_POST['MailUtilisateur']);
                $logHack->execute();
                header("location: register.php?hack_log=true");
            }
        } else {
            $generated_id = generateRandomString(5);
            echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Un compte utilisant cette adresse mail et/ou ce numéro de téléphone existe déjà. <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
        }
    } else {
        if (!empty($_POST)) {
            $generated_id = generateRandomString(5);
            echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')" > Merci de remplir tous les champs <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
        }
    }
    echo '<style> body{ background-image : url("Elements/backgrounds/background03.jpg");}</style>';
    require('menu.php');
    ?>

    <div style="display: flex; height: 90vh; flex-wrap: wrap; align-items: center; justify-content: center; align-content: flex-start;">
        <form method="post" class="form" style="color:white;background-color:rgba(0,0,0,0.5); backdrop-filter: blur(5px);-webkit-backdrop-filter: blur(5px);width: 80%;    max-width: 500px;" onsubmit="active_loader()">
            <img src="Elements/placeholder_logo.svg" alt="logo" style="width:100px;max-width: 750px;">

            <div id="error_container"></div>
            <form class="box" action="" method="post" style="display:flex;flex-direction:column;gap:15px">
                <div class="input_container">
                    <label for="NomUtilisateur">Nom</label>
                    <input type="text" required class="box-input" style="width:100%" name="NomUtilisateur" id="NomUtilisateur" autocomplete="new-name" placeholder="Entrez ici votre nom">
                </div>
                <div class="input_container">
                    <label for="PrenomUtilisateur">Prénom</label>
                    <input type="text" required class="box-input" style="width:100%" name="PrenomUtilisateur" id="PrenomUtilisateur" autocomplete="new-surname" placeholder="Entrez ici votre prénom">
                </div>
                <div class="input_container">
                    <label for="UsernameUtilisateur">Nom d'utilisateur</label>
                    <input type="text" required class="box-input" style="width:100%" name="UsernameUtilisateur" id="UsernameUtilisateur" placeholder="Entrez ici votre nom d'utilisateur">
                </div>
                <div class="input_container">
                    <label for="MailUtilisateur">Adresse Email</label>
                    <input type="email" pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" required class="box-input" style="width:100%" name="MailUtilisateur" id="MailUtilisateur" autocomplete="new-mail" placeholder="Entrez ici votre adresse Email">
                </div>
                <div class="input_container">
                    <label for="DiscordUtilisateur">Discord</label>
                    <input type="text" required class="box-input" style="width:100%" name="DiscordUtilisateur" id="DiscordUtilisateur" placeholder="Entrez ici votre pseudo Discord">
                </div>
                <div class="input_container">
                    <label for="ProfilUtilisateur">Profil</label>
                    <select name="ProfilUtilisateur" id="ProfilUtilisateur" required style="width:100%;color: black;">
                        <option value="mmi1">MMI 1</option>
                        <option value="mmi2">MMI 2</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="input_container">
                    <label for="FavGameUtilisateur">Jeu préféré</label>
                    <p class="error" id="game-error-msg">Veuillez choisir un jeux présent dans la liste. Toutes tentatives de hack est prohibée et sera sanctionnée.</p>
                    <select name="FavGameUtilisateur" id="FavGameUtilisateur" required style="width:100%;color: black;">
                        <?php
                        $query = $conn2->prepare("SELECT * 
									FROM games
									WHERE games.GameStatus != 'del'");
                        $query->execute();
                        $games = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($games as $game) {
                            echo '
                        <option value="' . $game['GameId'] . '">
                            ' . $game['GameName'] . '
                        </option>
                        ';
                        }

                        ?>
                    </select>
                </div>
                <div class="input_container">
                    <label for="TelUtilisateur">Numéro de téléphone</label>
                    <input type="tel" required class="box-input" style="width:100%;color: black;" name="TelUtilisateur" id="TelUtilisateur" autocomplete="new-tel" placeholder="Entrez ici votre numéro de Téléphone">
                </div>
                <div class="input_container">
                    <label for="MdpUtilisateur">Mot de passe</label>
                    <input type="password" required class="box-input" style="width:100%" name="MdpUtilisateur" id="MdpUtilisateur" autocomplete="new-password" placeholder="Entrez ici votre mot de passe">
                </div>
                <?php if ($redirect_join) {
                    echo '<input type="hidden" name="JoinId" value="' . $_GET['JoinId'] . '">';
                    echo '<input type="hidden" name="JoinToken" value="' . $_GET['JoinToken'] . '">';
                } ?>
                <div>
                    <input type="checkbox" required id="accept_conditions"> <label for="accept_conditions">J'ai lu et j'accepte les conditions </label>
                </div>
                <input type="submit" name="submit" style="font-weight:bold" value="S'inscrire" class="box-button " />
                <p class="links_txt">
                    Déjà inscrit ?
                    <?php if ($redirect_join) {
                        echo '<a href="login.php?JoinToken=' . htmlspecialchars($_GET['JoinToken'], ENT_QUOTES, 'UTF-8') . '&JoinId=' . htmlspecialchars($_GET['JoinId'], ENT_QUOTES, 'UTF-8') . '" class="links_txt " onclick="active_loader(); ">';
                    } else {
                        echo '<a href="login.php" class="links_txt " onclick="active_loader(); ">';
                    } ?>
                    Connectez-vous ici</a>
                </p>
            </form>
    </div>

    <br><br><br>
    <br><br><br>
    <br><br><br>

    <script src="js/main_script.js"></script>
</body>

</html>