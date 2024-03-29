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
    <?php
    include_once './includes/head.php';
    ?>
    <link rel="stylesheet" href="css/register.css" />


</head>

<body class="body-panel">
    <div class="frise_container">
        <div class="frise">
            <img src="Elements/others/Vector.svg" />
        </div>
    </div>

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

    $redirect_join = (isset($_GET['JoinId']) and isset($_GET['JoinToken']));
    define('MyConst', TRUE);
    require('app/config.php');
    session_start();
    if (isset($_SESSION["PlayerId"])) {
        header('location: profile');
        exit();
    }
    require('menu.php');
    $are_all_set = isset($_POST['NomUtilisateur'], $_POST['PrenomUtilisateur'], $_POST['MailUtilisateur'], $_POST['TelUtilisateur'], $_POST['MdpUtilisateur'], $_POST['DiscordUtilisateur'], $_POST['ProfilUtilisateur'], $_POST['UsernameUtilisateur']);
    $are_not_empty = (!empty($_POST['NomUtilisateur']) &&  !empty($_POST['PrenomUtilisateur']) &&  !empty($_POST['MailUtilisateur']) &&  !empty($_POST['TelUtilisateur']) &&  !empty($_POST['MdpUtilisateur']) &&  !empty($_POST['DiscordUtilisateur']) &&  !empty($_POST['ProfilUtilisateur']) &&  !empty($_POST['UsernameUtilisateur']));

    $checkNumOfPlayers = $conn2->prepare("SELECT COUNT(*) FROM players WHERE players.PlayerStatus = 'ok' AND players.PlayerRole NOT IN ('admin', 'staff')");
    $checkNumOfPlayers->execute();
    $numOfPlayers = $checkNumOfPlayers->fetchColumn();
    if ($numOfPlayers <= 60) {
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
                $check_games->bindValue(1, intval($_POST['FavGameUtilisateur']));
                $check_games->execute();
                $result3 = $check_games->fetchAll(PDO::FETCH_ASSOC);

                if ($result3[0]['NombreJeux'] == 1) {
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
                    $logconn->bindValue(1, "Account creation : " . $_POST['MailUtilisateur'] . " successfully created its account");
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

                    // join the team if tokens and id are set
                    if (isset($_POST['JoinId']) and isset($_POST['JoinToken'])) {
                        // check if the invitation exists
                        $checkInvitationStatus = $conn2->prepare("SELECT InvitationStatus
                        FROM invitations 
                        WHERE InvitationId = ? and InvitationToken = ?");
                        $checkInvitationStatus->bindValue(1, htmlspecialchars($_POST['JoinId'], ENT_QUOTES, 'UTF-8'));
                        $checkInvitationStatus->bindValue(2, htmlspecialchars($_POST['JoinToken'], ENT_QUOTES, 'UTF-8'));
                        $checkInvitationStatus->execute();
                        $resultInvitationStatus = $checkInvitationStatus->fetchAll(PDO::FETCH_ASSOC);

                        if ($resultInvitationStatus[0]['InvitationStatus'] != 'En cours') {
                            header('Location: register.php?error=Cette-invitation-n\'est-plus-valide');
                        } else if ($resultInvitationStatus[0]['InvitationStatus'] == 'En cours') {
                            $getTeamId = $conn2->prepare("SELECT InvitationTeamId
                        FROM invitations 
                        WHERE InvitationId = ? and InvitationToken = ?");
                            $getTeamId->bindValue(1, $invitationId);
                            $getTeamId->bindValue(2, $invitationToken);
                            $getTeamId->execute();
                            $resultTeamId = $getTeamId->fetchAll(PDO::FETCH_ASSOC);


                            $query = "SELECT IFNULL(MAX(AppartientId), 0) + 1 as NewAppartientId FROM appartient";
                            $NewAppartientId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment;

                            $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'AcceptÃƒÂ©e'
                                            WHERE InvitationId = ?
                                            AND InvitationToken = ?");

                            $query->bindValue(1, $invitationId);
                            $query->bindValue(2, $invitationToken);
                            $query->execute();

                            $query = $conn2->prepare("INSERT INTO appartient (AppartientId,AppartientPlayerId, AppartientTeamId, AppartientRole)
                                            VALUES (?, ?, ?, 'player')
                                            ");
                            $query->bindValue(1, $NewAppartientId['NewAppartientId']);
                            $query->bindValue(2, $result2['NewPlayerId']);
                            $query->bindValue(3, $resultTeamId[0]['InvitationTeamId']);
                            $query->execute();
                        }
                    }

                    // new session for the new player
                    $_SESSION["PlayerId"] = $result2['NewPlayerId'];
                    $_SESSION['PlayerMail'] = $_POST['MailUtilisateur'];


                    echo '<script type="text/javascript">window.location = "profile"</script>';
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
                echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Un compte utilisant cette adresse mail et/ou ce numéro de téléphone existe déjà . <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
            }
        } else {
            if (!empty($_POST)) {
                $generated_id = generateRandomString(5);
                echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')" > Merci de remplir tous les champs <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
            }
        }

    ?>
        <section id="inscription">
            <div class="inscription_form_container">
                <h2 class="head_title primary">Inscription</h2>
                <form method="post" onsubmit="active_loader()">
                    <div id="error_container" class="error" style="display: none;"></div>
                    <form action="" method="post">
                        <div class="inputs_container">
                            <div class="input-group">
                                <input type="text" required class="box-input" style="width:100%;" name="NomUtilisateur" id="NomUtilisateur" autocomplete="first-name" placeholder=" ">
                                <label for="NomUtilisateur">Nom</label>
                            </div>
                            <div class="input-group">
                                <input type="text" required class="box-input" style="width:100%" name="PrenomUtilisateur" id="PrenomUtilisateur" autocomplete="family-surname" placeholder=" ">
                                <label for="PrenomUtilisateur">Prénom</label>
                            </div>
                            <div class="input-group">
                                <input type="text" required class="box-input" style="width:100%" name="UsernameUtilisateur" id="UsernameUtilisateur" placeholder=" ">
                                <label for="UsernameUtilisateur">Nom d'utilisateur</label>
                            </div>
                            <div class="input-group">

                                <?php
                                $join_mail = "";
                                if ($redirect_join) {
                                    $query = $conn2->prepare("SELECT invitations.InvitationEmail FROM invitations WHERE InvitationId = ? and InvitationToken = ? and InvitationStatus = 'En cours'");
                                    $query->bindValue(1, $_GET['JoinId']);
                                    $query->bindValue(2, $_GET['JoinToken']);
                                    $query->execute();
                                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                                    if (!empty($result)) {
                                        $join_mail = $result[0]['InvitationEmail'];
                                    } else {
                                        $redirect_join = false;
                                    }
                                } else {
                                    $join_mail = "";
                                }
                                ?>
                                <input type="email" placeholder=" " pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" required class="box-input" style="width:100%" name="MailUtilisateur" id="MailUtilisateur" autocomplete="new-mail" value="<?= $join_mail ?>">
                                <label for="MailUtilisateur">Adresse Email</label>
                            </div>



                            <div class="input-group">
                                <input type="text" required class="box-input" style="width:100%" name="DiscordUtilisateur" id="DiscordUtilisateur" placeholder=" ">
                                <label for="DiscordUtilisateur">Discord</label>
                            </div>
                            <div class="input-group">
                                <input type="tel" required class="box-input" style="width:100%;color: black;" name="TelUtilisateur" id="TelUtilisateur" autocomplete="new-tel" placeholder=" ">
                                <label for="TelUtilisateur">Numéro de téléphone</label>
                            </div>
                            <div class="input-group normal_label">
                                <label for="FavGameUtilisateur">Jeu favori :</label>
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
                            <div class="input-group normal_label">
                                <label for="ProfilUtilisateur">Promo :</label>
                                <select name="ProfilUtilisateur" id="ProfilUtilisateur" required style="width:100%;color: black;">
                                    <option value="mmi1">MMI 1</option>
                                    <option value="mmi2">MMI 2</option>
                                    <option value="enseignant">Enseignant</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <input type="password" required class="box-input" style="width:100%" name="MdpUtilisateur" id="MdpUtilisateur" autocomplete="new-password" placeholder=" ">
                                <label class="mail_input" for="MdpUtilisateur">Mot de passe</label>
                            </div>

                        </div>
                        <div class="end_of_form">
                            <?php if ($redirect_join) {
                                echo '<input type="hidden" name="JoinId" value="' . $_GET['JoinId'] . '">';
                                echo '<input type="hidden" name="JoinToken" value="' . $_GET['JoinToken'] . '">';
                            } ?>
                            <div class="conditions_text">
                                <input type="checkbox" required id="accept_conditions"> <label for="accept_conditions">J'ai lu et j'accepte <a href="./docs/reglement_LAN_VF.pdf" style="text-decoration: underline;" target="_blank">les conditions</a> </label>
                                <br><br>
                                <input type="checkbox" required id="accept_image_exploitations"> <label for="accept_image_exploitations">J'accepte les règles de droits à l'image.</label>
                            </div>
                            <input class="btn btn__primary btn_submit" type="submit" name="submit" value="S'inscrire" />

                            <p class="links_txt">
                                Déjà inscrit ?
                                <?php if ($redirect_join) {
                                    echo '<a href="login.php?JoinToken=' . htmlspecialchars($_GET['JoinToken'], ENT_QUOTES, 'UTF-8') . '&JoinId=' . htmlspecialchars($_GET['JoinId'], ENT_QUOTES, 'UTF-8') . '" class="links_txt " onclick="active_loader(); ">';
                                } else {
                                    echo '<a href="login.php" class="links_txt " onclick="active_loader(); ">';
                                } ?>
                                Connectez-vous ici</a>
                            </p>
                        </div>
                    </form>
            </div>

            <!-- <br><br><br>
    <br><br><br>
    <br><br><br> -->
        </section>




    <?php

    } else {
    ?>
        <div class="container" style="height:100vh;">
            <div class="error">Les inscriptions sont complètes ! Si vous avez une demande particulière demandez nous sur le discord ou par mail : mmilan.tln@gmail.com</div>
        </div>
    <?php
    }

    include_once './includes/footer.php';
    ?>

</body>

</html>