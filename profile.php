<?php

/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 * @author     Mathis Lambert
 *
 * Contributors :
 * -
 *
 */

session_start();
define('MyConst', TRUE);
require('app/config.php');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './lib/PHPMailer/src/Exception.php';
require './lib/PHPMailer/src/PHPMailer.php';
require './lib/PHPMailer/src/SMTP.php';


if (!isset($_SESSION["PlayerId"])) {
    header("Location: index.php");
    exit();
} else {
    $query = $conn2->prepare("SELECT * 
									FROM players
									WHERE players.PlayerStatus = 'ok'
									and players.PlayerId = ?");
    $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) { // vérifie que l'utilisateur n'est pas bloqué
        header("Location: logout.php?blocked=true");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Mon compte</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
    <link rel="stylesheet" href="css/loader.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/main_script.js"></script>
</head>

<body style="padding-top:50px">
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
    <div class="container">
        <?php
        require('menu.php');

        // Easter Egg - NE PAS TOUCHER
        goto yZY1K;
        AaKGv:
        echo "\74\150\x33\x3e\x54\47\x65\163\40\161\x75\x69\x20\x74\157\x69\40\x3f\x20\x44\303\251\x67\x61\x72\160\x69\154\154\x65\x2e\74\57\x68\x33\x3e";
        goto QZsCH;
        yZY1K:
        if (!(strpos(strtolower($result[0]["\x50\x6c\x61\x79\145\x72\x4c\x61\163\x74\156\x61\x6d\x65"]), "\x70\141\x72\166\x65\x69\x7a") !== false or strpos(strtolower($result[0]["\120\154\141\171\x65\x72\125\x73\145\162\x6e\141\x6d\145"]), "\155\x75\156\x65\x72\141") !== false or strpos($result[0]["\x50\x6c\141\171\x65\162\104\x69\x73\143\x6f\x72\x64"], "\x4d\x75\156\x65\162\x61\43\65\61\60\66") !== false)) {
            goto i09Iu;
        }
        goto QYsJy;
        QYsJy:
        echo "\x3c\x62\x72\x3e";
        goto AaKGv;
        QZsCH:
        echo "\x3c\x69\x6d\x67\x20\x73\x72\143\75\42\x45\154\145\155\145\x6e\164\163\x2f\x6f\x74\150\x65\x72\x73\x2f\x74\x5f\145\x73\137\x71\165\151\137\164\157\151\56\x67\151\146\x22\x20\x61\x6c\x74\75\x22\124\x27\145\163\x20\x71\165\x69\x20\164\x6f\151\40\x3f\x20\x64\xc3\251\147\141\x72\160\x69\154\x6c\x65\56\x22\40\x73\164\171\154\x65\x3d\42\155\141\x78\x2d\167\x69\144\164\x68\x3a\x20\x33\x30\60\x70\170\x3b\42\x3e";
        goto mJp_X;
        mJp_X:
        i09Iu:
        // FIN - Easter Egg - NE PAS TOUCHER

        if (isset($_POST['msg']) and $_POST['msg'] === "successfullAddedTeam") {
            $generated_id = generateRandomString(5);
            echo '<div class="modal success" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')" > Équipe créée avec succès. <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
        }

        $query = $conn2->prepare("SELECT * 
									FROM players, appartient, teams
									WHERE players.PlayerStatus = 'ok'
									and teams.TeamStatus = 'ok'
									and appartient.AppartientStatus not in ('del','canceled')
									and players.PlayerId = ?
                                    and players.PlayerId = appartient.AppartientPlayerId
                                    and appartient.AppartientTeamId = teams.TeamId
									");
        $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
        $query->execute();
        $playerTeam = $query->fetchAll(PDO::FETCH_ASSOC); // données sur l'équipe du joueur

        if (!empty($playerTeam)) { // appartient à une équipe
            $playerTeam = $playerTeam[0];
            echo 'Votre équipe !
            <h4>' . $playerTeam['TeamName'] . '</h4>
            <p>' . $playerTeam['TeamDesc'] . '</p>
            <img src="' . $playerTeam['TeamLogo'] . '" alt="team_logo" style="width: 100px;">
            <br>
            ';
            if ($playerTeam['AppartientRole'] === "chef") { // affichage en tant que chef d'équipe
                $query = $conn2->prepare("SELECT * 
									FROM players, appartient, teams
									WHERE players.PlayerStatus = 'ok'
									and teams.TeamStatus = 'ok'
									and appartient.AppartientStatus not in ('del','canceled')
                                    and players.PlayerId = appartient.AppartientPlayerId
                                    and appartient.AppartientTeamId = teams.TeamId
                                    and appartient.AppartientTeamId = ?
									");
                $query->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                $query->execute();
                $playersOfPlayerTeam = $query->fetchAll(PDO::FETCH_ASSOC); //

                $number_of_players = count($playersOfPlayerTeam);

                if (!empty($playersOfPlayerTeam)) {
                    echo '<h4>Membres de votre équipe</h4>';
                    foreach ($playersOfPlayerTeam as $player) {
                        echo '<p>' . $player['PlayerLastname'] . ' ' . $player['PlayerFirstname'] . ' - ' . $player['AppartientRole'] . '</p>';
                    }
                } else {
                    echo 'Il n\'y a aucun membre dans votre équipe';
                }


                // si invitations lancées, affichage statut
                // si aucunes invitations, demander mail membres, vérifier qu'ils n'appartiennent pas à une autre équipe, insérer en bdd invitation avec status en attente
                $query = $conn2->prepare("SELECT *
                                    FROM invitations
                                    WHERE invitations.InvitationStatus = 'En cours'
                                    and invitations.InvitationTeamId = ?
                                    ");
                $query->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                $query->execute();
                $invitations = $query->fetchAll(PDO::FETCH_ASSOC);

                if ($number_of_players === 3) {
                    echo '<p>Vous avez atteint le nombre maximum de membres dans votre équipe</p>';
                } else {
                    //checker si invitations en cours
                    $checkInvitations = $conn2->prepare("SELECT *
                                                        FROM invitations, teams
                                                        WHERE invitations.InvitationTeamId = teams.TeamId
                                                        and invitations.InvitationTeamId = ?
                                                        and invitations.InvitationStatus = 'En cours'
                                                        ");
                    $checkInvitations->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                    $checkInvitations->execute();
                    $invitations = $checkInvitations->fetchAll(PDO::FETCH_ASSOC);

                    if ($number_of_players === 2) {
                        // si membres équipe === 2 , l'afficher + statut invitation en cours (en cours, refusé,etc..)
                        if (!empty($invitations)) {
                            echo '<h4>Invitations en cours :</h4>
                            <ul>';
                            foreach ($invitations as $invitation) {
                                echo '<li>' . $invitation['InvitationEmail'] . ' - ' . $invitation['InvitationStatus'] . '</li>';
        ?>
                                <form action="" method="post">
                                    <input type="hidden" name="invitationId" value="<?php echo $invitation['InvitationId']; ?>">
                                    <input type="submit" name="cancelInvitation" value="Annuler l'invitation">
                                </form>
        <?php
                            }
                            echo '</ul>';
                        } else {
                            echo 'Vous pouvez inviter un joueur à rejoindre votre équipe en utilisant le formulaire ci-dessous.';
                            // formulaire d'invitation pour le joueur restant

                            echo
                            '<form action="" method="post">
                            <input type="email" name="playerToInvite" placeholder="Email du joueur à inviter">
                            <input type="hidden" name="teamId" value="' . $playerTeam['TeamId'] . '">
                            <input type="submit" value="Inviter" name="monoInvitation">
                            </form>';
                        }
                    } elseif ($number_of_players === 1) {
                        if (!empty($invitations)) {
                            if (count($invitations) === 2) {
                                echo 'Membres de votre équipe :
                                <ul>';
                                foreach ($playersOfPlayerTeam as $player) {
                                    echo '<li>' . $player['PlayerUsername'] . '</li>';
                                }
                                echo '</ul>';
                                // Toutes les invitations
                                echo 'Vos invitations : 
                                <ul>';
                                foreach ($invitations as $invitation) {
                                    echo '<li>' . $invitation['PlayerUsername'] . ' - ' . $invitation['InvitationStatus'] . '</li>';
                                }
                                echo '</ul>';
                            } elseif (count($invitations) === 1) {
                                echo 'Membres de votre équipe :
                                <ul>';
                                foreach ($playersOfPlayerTeam as $player) {
                                    echo '<li>' . $player['PlayerUsername'] . '</li>';
                                }
                                echo '</ul>';
                                // Toutes les invitations
                                echo 'Vos invitations : 
                                <ul>';
                                foreach ($invitations as $invitation) {
                                    echo '<li>' . $invitation['PlayerUsername'] . ' - ' . $invitation['InvitationStatus'] . '</li>';
                                }
                                echo '</ul>';


                                echo 'Vous pouvez inviter un joueur à rejoindre votre équipe en utilisant le formulaire ci-dessous.';
                                // formulaire d'invitation pour le joueur restant
                                echo
                                '<form action="" method="post">
                                    <input type="email" name="playerToInvite" placeholder="Email du joueur à inviter">
                                    <input type="hidden" name="teamId" value="' . $playerTeam['TeamId'] . '">
                                    <input type="submit" value="Inviter">
                                </form>';
                            }
                        }
                    }
                }

                // checking POSTS 
                if (isset($_POST['monoInvitation'])) {
                    // check si email existe
                    $checkPlayer = $conn2->prepare("SELECT *
                                                    FROM players
                                                    WHERE players.PlayerEmail = ?
                                                    ");
                    $checkPlayer->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                    $checkPlayer->execute();
                    $player = $checkPlayer->fetch(PDO::FETCH_ASSOC);
                    // var_dump($player);
                    if (!empty($player)) {
                        // check si email est déjà invité
                        $checkInvitation = $conn2->prepare("SELECT *
                                                            FROM invitations
                                                            WHERE invitations.InvitationEmail = ?
                                                            and invitations.InvitationTeamId = ?
                                                            ");
                        $checkInvitation->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                        $checkInvitation->bindValue(2, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                        $checkInvitation->execute();
                        $invitation = $checkInvitation->fetch(PDO::FETCH_ASSOC);
                        // var_dump($invitation);
                        if (empty($invitation)) {
                            // check si email est déjà membre de l'équipe
                            $checkPlayerOfTeam = $conn2->prepare("SELECT *
                                                                FROM players, teams
                                                                WHERE players.PlayerEmail = ?
                                                                and teams.TeamId = ?
                                                                and players.PlayerTeamId = teams.TeamId
                                                                ");
                            $checkPlayerOfTeam->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                            $checkPlayerOfTeam->bindValue(2, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                            $checkPlayerOfTeam->execute();
                            $playerOfTeam = $checkPlayerOfTeam->fetch(PDO::FETCH_ASSOC);
                            // var_dump($playerOfTeam);
                            if (empty($playerOfTeam)) {
                                // check si email est déjà invité par un autre membre de l'équipe
                                $checkInvitationByOther = $conn2->prepare("SELECT *
                                                                        FROM invitations, players, teams
                                                                        WHERE invitations.InvitationEmail = ?
                                                                        and invitations.InvitationTeamId = teams.TeamId
                                                                        and teams.TeamId = ?
                                                                        and invitations.InvitationEmail = players.PlayerEmail
                                                                        and players.PlayerTeamId = teams.TeamId
                                                                        ");
                                $checkInvitationByOther->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                $checkInvitationByOther->bindValue(2, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                                $checkInvitationByOther->execute();
                                $invitationByOther = $checkInvitationByOther->fetch(PDO::FETCH_ASSOC);
                                // var_dump($invitationByOther);

                            }
                        }
                    } else {
                        echo "<p>Ce joueur n'a pas encore de compte.</p>";
                        echo "Un mail lui sera envoyé pour l'inviter à créer un compte.";
                        // envoi mail
                        //génération d'un token
                        $token = generateRandomString();

                        // obtention des parametres de connexion au smtp
                        $smtp = $conn2->prepare("SELECT settings.settingsValue FROM `settings` WHERE settings.settingsName = 'instance_email_host'");
                        $smtp->execute();
                        $smtp = $smtp->fetch(PDO::FETCH_ASSOC);

                        $port = $conn2->prepare("SELECT settings.settingsValue FROM `settings` WHERE settings.settingsName = 'instance_email_port'");
                        $port->execute();
                        $port = $port->fetch(PDO::FETCH_ASSOC);

                        $username = $conn2->prepare("SELECT settings.settingsValue FROM `settings` WHERE settings.settingsName = 'instance_email_username'");
                        $username->execute();
                        $username = $username->fetch(PDO::FETCH_ASSOC);

                        $password = $conn2->prepare("SELECT settings.settingsValue FROM `settings` WHERE settings.settingsName = 'instance_email_password'");
                        $password->execute();
                        $password = $password->fetch(PDO::FETCH_ASSOC);

                        $mailArr = [
                            'host' => $smtp['settingsValue'],
                            'port' => $port['settingsValue'],
                            'username' => $username['settingsValue'],
                            'password' => $password['settingsValue'],
                        ];


                        try {
                            // Création du mail
                            $mail = new PHPMailer(true);
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = $mailArr['host'];           //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = $mailArr['username'];       //SMTP username
                            $mail->Password   = $mailArr['password'];       //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                            $mail->Port       = 465;           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            //Recipients
                            $mail->setFrom($mailArr['username'], 'MMI LAN');
                            $mail->addAddress($_POST['playerToInvite']);     //Add a recipient

                            //Content
                            $mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = 'Here is the subject';
                            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                            $mail->send();
                            echo 'Message has been sent';

                            // enregistrement de l'invitation
                            $insertInvitation = $conn2->prepare("INSERT INTO invitations (InvitationEmail, InvitationTeamId, InvitationStatus, InvitationToken)
                                                            VALUES (?, ?, ?, ?)");
                            $insertInvitation->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                            $insertInvitation->bindValue(2, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                            $insertInvitation->bindValue(3, 'En cours');
                            $insertInvitation->bindValue(4, $token);
                            $insertInvitation->execute();
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    }
                }

                if (isset($_POST['cancelInvitation'])) {
                    $cancelInvitation = $conn2->prepare("DELETE FROM invitations WHERE InvitationId = ?");
                    $cancelInvitation->bindValue(1, htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8'));
                    $cancelInvitation->execute();
                }
            } else { // affichage en tant que membre équipe
                // afficher les membres de l'équipe
                $query = $conn2->prepare("SELECT * 
									FROM players, appartient, teams
									WHERE players.PlayerStatus = 'ok'
									and teams.TeamStatus = 'ok'
									and appartient.AppartientStatus not in ('del','canceled')
                                    and players.PlayerId = appartient.AppartientPlayerId
                                    and appartient.AppartientTeamId = teams.TeamId
                                    and appartient.AppartientTeamId = ?
									");
                $query->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                $query->execute();
                $playersOfPlayerTeam = $query->fetchAll(PDO::FETCH_ASSOC); //

                foreach ($playersOfPlayerTeam as $playerOfPlayerTeam) {
                    echo '<p>' . $playerOfPlayerTeam['PlayerFirstname'] . ' ' . $playerOfPlayerTeam['PlayerLastname'] . ' (' . $playerOfPlayerTeam['AppartientRole'] . ')</p>';
                }
            }
        } else {
            //check invitations of the player
            $checkInvitation = $conn2->prepare("SELECT *
                                                FROM players, invitations, teams
                                                WHERE players.PlayerStatus = 'ok'
                                                and teams.TeamStatus = 'ok'
                                                and invitations.InvitationStatus not in ('del','canceled')
                                                and invitations.InvitationTeamId = teams.TeamId
                                                and invitations.InvitationEmail = players.PlayerEmail
                                                and invitations.InvitationEmail = ?
                                                ");
            $checkInvitation->bindValue(1, htmlspecialchars($_SESSION['PlayerMail'], ENT_QUOTES, 'UTF-8'));
            $checkInvitation->execute();
            $invitation = $checkInvitation->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($invitation)) {
                echo "<h1> Vos invitations </h1>";
                // si invitation en cours, afficher statut
                echo 'Vous avez une invitation en attente.';
                echo '<ul>';
                echo '<li>' . $invitation[0]['TeamName'] . ' - ' . $invitation[0]['InvitationStatus'] . '</li>';
                echo '</ul>';
                echo '<form action="" method="post">
                        <input type="hidden" name="invitationId" value="' . $invitation[0]['InvitationId'] . '">
                        <input type="hidden" name="teamId" value="' . $invitation[0]['TeamId'] . '">
                        <input type="submit" name="acceptInvitation" value="Accepter">
                        <input type="submit" name="refuseInvitation" value="Refuser">
                    </form>';
            } else {
                // si aucune invitation, afficher formulaire pour créer une équipe
                echo 'Vous n\'avez pas d\'équipe. Vous pouvez en créer une en utilisant le formulaire ci-dessous.';
                echo
                '<form action="" method="post">
                    <input type="text" name="teamName" placeholder="Nom de l\'équipe">
                    <input type="submit" value="Créer">
                </form>';
            }

            if (isset($_POST['acceptInvitation'])) {
                $invitationId = htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8');
                $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'Acceptée'
                                            WHERE InvitationId = ?
                                            ");
                $query->bindValue(1, $invitationId);
                $query->execute();

                $query = $conn2->prepare("INSERT INTO appartient (AppartientPlayerId, AppartientTeamId, AppartientRole)
                                            VALUES (?, ?, 'player')
                                            ");
                $query->bindValue(1, $_SESSION['PlayerId']);
                $query->bindValue(2, $_POST['teamId']);
                $query->execute();
            } elseif (isset($_POST['refuseInvitation'])) {
                $invitationId = htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8');
                $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'Refusée'
                                            WHERE InvitationId = ?
                                            ");
                $query->bindValue(1, $invitationId);
                $query->execute();

                header('Location: team.php');
            }

            if (isset($_POST['TeamName']) and isset($_POST['TeamDesc']) and isset($_FILES['TeamLogo'])) { // si le formulaire pour créer une équipe est rempli :
                $upload = true;
                $extensions = array('image/jpeg', 'image/jpg', 'image/png');
                if ($_FILES['TeamLogo']['size'] <= 0) {
                    echo ' <form method="post" id="refresher" action="index.php"><input type="hidden" name="msg" value="errorAddingBadgeMinSize"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
                    $upload = false;
                }
                if ($_FILES['TeamLogo']['size'] > 3000000) {
                    echo ' <form method="post" id="refresher" action="index.php"><input type="hidden" name="msg" value="errorAddingBadgeMaxSize"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
                    $upload = false;
                }
                if (!in_array($_FILES['TeamLogo']['type'], $extensions)) {
                    echo ' <form method="post" id="refresher" action="index.php"><input type="hidden" name="msg" value="errorAddingBadgeTypeNotAllowed"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
                    $upload = false;
                }
                if ($upload) { // upload respecte toutes les conditions
                    $file = 'data:' . $_FILES['TeamLogo']['type'] . ';base64,' . base64_encode(file_get_contents($_FILES['TeamLogo']['tmp_name']));

                    $query = "SELECT IFNULL(MAX(TeamId), 0) + 1 as NewTeamId FROM teams";
                    $newTeamId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment

                    $query = $conn2->prepare("INSERT INTO teams 
                                    (TeamId, TeamName, TeamDesc, TeamLogo)
                                    VALUES (?, ?, ?, ?)
                                  ");
                    $query->bindValue(1, $newTeamId["NewTeamId"]);
                    $query->bindValue(2, htmlspecialchars($_POST['TeamName'], ENT_QUOTES, 'UTF-8'));
                    $query->bindValue(3, htmlspecialchars($_POST['TeamDesc'], ENT_QUOTES, 'UTF-8'));
                    $query->bindValue(4, $file);
                    $query->execute();

                    $query = $conn2->prepare("INSERT INTO appartient 
                                    (AppartientPlayerId, AppartientTeamId, AppartientRole)
                                    VALUES (?, ?, 'chef')
                                  ");

                    $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                    $query->bindValue(2, $newTeamId["NewTeamId"]);
                    $query->execute();

                    echo ' <form method="post" id="refresher" ><input type="hidden" name="msg" value="successfullAddedTeam"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
                    die();
                }
            } elseif (isset($_POST['select']) and in_array($_POST['select'], ['team', 'solo'])) { // si le bouton solo ou chef d'équipe a été cliqué
                echo '
        <form method="post">
            <button type="submit" class="N_button">Retour</button>
        </form>
        ';
                if ($_POST['select'] === "team") { // si le bouton chef d'équipe a été cliqué
                    echo ' inscrivez votre équipe';
                    echo '
                    <form method="post" enctype="multipart/form-data" style="display: flex;flex-direction: column;gap: 15px">
                        <input type="text" name="TeamName" required minlength="3" placeholder="Nom de l\'équipe">
                        <textarea name="TeamDesc" required minlength="15" placeholder="Description de l\'équipe" style="resize: none;height: 80px;padding-top: 10px;"></textarea>
                        <input type="file" name="TeamLogo" required accept="image/*">
                        <button class="Y_button" type="submit">Enregistrer l\'équipe</button>
                    </form>
                    ';
                } elseif ($_POST['select'] === "solo") { // si le bouton solo a été cliqué
                    // s'inscrire en tant que joueur solo
                    // afficher "êtes-vous sûr de pas vouloir rejoindre [EXEMPLE] ? Cela va annuler votre invitation." (si une invitation est désignée à ce joueur).

                }
            } else { // aucune équipe
                //vérifier en bdd si une invitation a été adressée au joueur connecté, possibilité d'accepter ou refuser = changer statut de l'invitation en bdd, et de rejoindre, ou pas, l'équipe
                echo '
        <form method="post">
            <input type="hidden" name="select" value="team">
            <button type="submit">Je crée une équipe</button>
        </form>
        ';
                echo '
        <form method="post">
            <input type="hidden" name="select" value="solo">
            <button type="submit">Je suis solo pour le moment</button>
        </form>
        ';
            }
        }

        ?>

    </div>