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

require 'lib/PHPMailer/src/Exception.php';
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';

$email_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/valid_check.gif" style="width:150px">
            <h3>Demande d\'invitation envoyee !!</h3>

       </div> <br><br><br>';
$email_not_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/invalid_cross.gif" style="width:150px">
            <h3>Erreur d\'envoi</h3>
            <p>Une erreur s\'est produite lors de l\'envoi du mail. </p>

       </div> <br><br><br>';
$invalid_email_or_already_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/invalid_cross.gif" style="width:150px">
            <h3>Erreur d\'envoi</h3>
            <p>Il semblerai qu\'aucun compte n\'est associé à cette adresse mail. <br>Ou une réinitialisation esr déjà en cours. </p>

       </div> <br><br><br>';
$expired_recover = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/invalid_cross.gif" style="width:150px">
            <h3>Demande expirée</h3>
            <p>Votre demande de réinitialisation a expire. Veuillez réitérer votre demande </p>

       </div> <br><br><br>';


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
    <?php
    include_once './includes/head.php';
    ?>


</head>

<body style="min-height: 100vh;">
    <div class="frise_container">
        <div class="frise">
            <img src="./Elements/others/Vector.svg">
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
    if (isset($_POST['leaveSolo'])) {
        $setOld = $conn2->prepare("UPDATE appartient_solo
                                    SET AppartientSoloStatus = 'old'
                                    WHERE AppartientSoloPlayerId = ?
                                    ");
        $setOld->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
        $setOld->execute();
        header("Location: profile.php");
        exit();
    }
    if (isset($_POST['acceptInvitation'])) {
        // check veracity of the invitation
        $query = $conn2->prepare("SELECT * 
                                    FROM players, invitations, teams
                                    WHERE invitations.InvitationStatus = 'pending'
                                    and invitations.InvitationTeamId = teams.TeamId
                                    and invitations.InvitationEmail = players.PlayerEmail
                                    and invitations.InvitationId = ?
                                    ");
        $query->bindValue(1, htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8'));
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            if ($result[0]['PlayerEmail'] === $_SESSION['PlayerMail']) {
                /* Check if player is part of appartient_solo */
                $query = $conn2->prepare("SELECT * 
                                    FROM players, appartient_solo
                                    WHERE players.PlayerStatus = 'ok'
                                    and appartient_solo.AppartientSoloStatus not in ('ancien','old')
                                    and players.PlayerId = ?
                                    and players.PlayerId = appartient_solo.AppartientSoloPlayerId
                                    ");
                $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                $query->execute();
                $playerSolo = $query->fetchAll(PDO::FETCH_ASSOC); // données sur le joueur

                // set AppartientSoloStatus to 'old'
                if (!empty($playerSolo)) {
                    $setOld = $conn2->prepare("UPDATE appartient_solo
                                    SET AppartientSoloStatus = 'old'
                                    WHERE AppartientSoloPlayerId = ?
                                    ");
                    $setOld->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                    $setOld->execute();
                }

                $query = "SELECT IFNULL(MAX(AppartientId), 0) + 1 as NewAppartientId FROM appartient";
                $NewAppartientId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment;

                $invitationId = htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8');
                $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'accepted'
                                            WHERE InvitationId = ?
                                            ");
                $query->bindValue(1, $invitationId);
                $query->execute();

                $query = $conn2->prepare("INSERT INTO appartient (AppartientId,AppartientPlayerId, AppartientTeamId, AppartientRole)
                                            VALUES (?, ?, ?, 'player')
                                            ");
                $query->bindValue(1, $NewAppartientId['NewAppartientId']);
                $query->bindValue(2, $_SESSION['PlayerId']);
                $query->bindValue(3, $result[0]['TeamId']);
                $query->execute();

                header("Location: profile.php");
            } else {
                echo '<p class="error">Erreur : Vous n\'êtes pas le destinataire de cette invitation</p>';
                exit();
            }
        } else {
            echo '<p class="error">Erreur : Cette invitation n\'existe pas ou a expirée</p>';
            exit();
        }
    } elseif (isset($_POST['refuseInvitation'])) {
        // check veracity of the invitation
        $query = $conn2->prepare("SELECT * 
                                    FROM players, invitations, teams
                                    WHERE invitations.InvitationStatus = 'pending'
                                    and invitations.InvitationTeamId = teams.TeamId
                                    and invitations.InvitationEmail = players.PlayerEmail
                                    and invitations.InvitationId = ?
                                    ");
        $query->bindValue(1, htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8'));
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            if ($result[0]['PlayerEmail'] === $_SESSION['PlayerMail']) {
                $invitationId = htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8');
                $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'denied'
                                            WHERE InvitationId = ?
                                            ");
                $query->bindValue(1, $invitationId);
                $query->execute();

                header('Location: ' . $_SERVER['REQUEST_URI']);
            } else {
                echo '<p class="error">Erreur : Vous n\'êtes pas le destinataire de cette invitation</p>';
                exit();
            }
        } else {
            echo '<p class="error">Erreur : Cette invitation n\'existe pas ou a expirée</p>';
            exit();
        }
    }
    if (isset($_POST['cancelInvitation'])) {
        // check veracity of the invitation
        $getTeam = $conn2->prepare("SELECT * 
                                    FROM players, teams, appartient
                                    WHERE appartient.AppartientStatus = 'ok'
                                    and appartient.AppartientTeamId = teams.TeamId
                                    and appartient.AppartientPlayerId = players.PlayerId
                                    and appartient.AppartientPlayerId = ?
                                    ");
        $getTeam->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
        $getTeam->execute();
        $team = $getTeam->fetch(PDO::FETCH_ASSOC);

        $teamId = $team['TeamId'];

        $query = $conn2->prepare("SELECT * 
                                    FROM players, invitations, teams
                                    WHERE invitations.InvitationStatus = 'pending'
                                    and invitations.InvitationTeamId = teams.TeamId
                                    and invitations.InvitationEmail = players.PlayerEmail
                                    and invitations.InvitationId = ?
                                    and invitations.InvitationTeamId = ?
                                    ");
        $query->bindValue(1, htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8'));
        $query->bindValue(2, $teamId);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $cancelInvitation = $conn2->prepare("UPDATE invitations
                                                SET invitations.InvitationStatus = 'cancelled'
                                                WHERE invitations.InvitationId = ?
                                                ");
            $cancelInvitation->bindValue(1, htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8'));
            $cancelInvitation->execute();

            header('Location: ' . $_SERVER['REQUEST_URI']);
        } else {
            echo '<p class="error">Erreur : Cette invitation n\'existe pas ou a expirée</p>';
            exit();
        }


        if (false) {
            $query = $conn2->prepare("SELECT * 
                                    FROM players, invitations, teams
                                    WHERE invitations.InvitationStatus = 'pending'
                                    and invitations.InvitationTeamId = teams.TeamId
                                    and invitations.InvitationEmail = players.PlayerEmail
                                    and invitations.InvitationId = ?
                                    and invitations.InvitationTeamId = ?
                                    ");
            $query->bindValue(1, htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8'));

            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                if ($result[0]['TeamId'] === $_SESSION['TeamId']) {
                    $invitationId = htmlspecialchars($_POST['invitationId'], ENT_QUOTES, 'UTF-8');
                    $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'cancelled'
                                            WHERE InvitationId = ?
                                            ");
                    $query->bindValue(1, $invitationId);
                    $query->execute();

                    header('Location: ' . $_SERVER['REQUEST_URI']);
                } else {
                    echo '<p class="error">Erreur : Vous n\'êtes pas le propriétaire de cette invitation</p>';
                    exit();
                }
            } else {
                echo '<p class="error">Erreur : Cette invitation n\'existe pas ou a expirée</p>';
                exit();
            }
        }
    }
    require('menu.php');
    ?>
    <div class="container">
        <br>
        <a class="btn btn__secondary" href="logout.php" style="text-decoration: none;">Se déconnecter</a>
        <br><br>
        <?php
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
									and teams.TeamStatus NOT IN ('banned', 'ban')
									and appartient.AppartientStatus not in ('del','canceled')
									and players.PlayerId = ?
                                    and players.PlayerId = appartient.AppartientPlayerId
                                    and appartient.AppartientTeamId = teams.TeamId
									");
        $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
        $query->execute();
        $playerTeam = $query->fetchAll(PDO::FETCH_ASSOC); // données sur l'équipe du joueur

        /* Check if player is part of appartient_solo */
        $query = $conn2->prepare("SELECT * 
                                    FROM players, appartient_solo
                                    WHERE players.PlayerStatus = 'ok'
                                    and appartient_solo.AppartientSoloStatus not in ('ancien','old')
                                    and players.PlayerId = ?
                                    and players.PlayerId = appartient_solo.AppartientSoloPlayerId
                                    ");
        $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
        $query->execute();
        $playerSolo = $query->fetchAll(PDO::FETCH_ASSOC); // données sur le joueur

        if (!empty($playerTeam)) { // appartient à une équipe
            $playerTeam = $playerTeam[0];
            echo '<h2 class="head_title primary">Votre Equipe !</h2>
            <div class="profile_head">
            <div class="profile_title">
            <label for="team_name">Nom de l\'équipe</label>
            <h1 id="team_name">' . $playerTeam['TeamName'] . '</h1>
            </div>

            <img src="' . $playerTeam['TeamLogo'] . '" alt="team_logo" style="width: 100px;">
            </div>

            <label for="team_desc">Description</label>
            <p class="team_desc" id="team_desc">' . $playerTeam['TeamDesc'] . '</p>
            <br>
            ';
            if ($playerTeam['AppartientRole'] === "chef") { // affichage en tant que chef d'équipe
                $query = $conn2->prepare("SELECT * 
									FROM players, appartient, teams
									WHERE players.PlayerStatus = 'ok'
									and teams.TeamStatus NOT IN ('banned', 'ban')
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
                    echo '<h3>Membres de votre equipe</h3>';
                    foreach ($playersOfPlayerTeam as $player) {
                        echo '<p>' . $player['PlayerLastname'] . ' ' . $player['PlayerFirstname'] . ' - ' . $player['AppartientRole'] . '</p>';
                    }
                } else {
                    echo 'Il n\'y a aucun membre dans votre equipe';
                }


                // si invitations lancées, affichage statut
                // si aucunes invitations, demander mail membres, vérifier qu'ils n'appartiennent pas à une autre équipe, insérer en bdd invitation avec status en attente
                $query = $conn2->prepare("SELECT *
                                    FROM invitations
                                    WHERE invitations.InvitationStatus = 'pending'
                                    and invitations.InvitationTeamId = ?
                                    ");
                $query->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                $query->execute();
                $invitations = $query->fetchAll(PDO::FETCH_ASSOC);

                if ($number_of_players === 3) {
                    echo '<br /><h4 class="success">Vous avez atteint le nombre maximum de membres dans votre equipe</h4>';
                } else {
                    //checker si invitations en cours
                    $checkInvitations = $conn2->prepare("SELECT *
                                                        FROM invitations, teams
                                                        WHERE invitations.InvitationTeamId = teams.TeamId
                                                        and invitations.InvitationTeamId = ?
                                                        and invitations.InvitationStatus = 'pending'
                                                        ");
                    $checkInvitations->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                    $checkInvitations->execute();
                    $invitations = $checkInvitations->fetchAll(PDO::FETCH_ASSOC);

                    if ($number_of_players === 2) {
                        // si membres équipe === 2 , l'afficher + statut invitation en cours (en cours, refusé,etc..)
                        if (!empty($invitations)) {
                            echo
                            '<h4>Invitations en cours :</h4>';

                            echo '<ul>';
                            foreach ($invitations as $invitation) {
                                echo '<li>' . $invitation['InvitationEmail'] . ' - ' . $invitation['InvitationStatus'] . '</li>';
        ?>
                                <form action="" method="post">
                                    <input type="hidden" name="invitationId" value="<?php echo $invitation['InvitationId']; ?>">
                                    <button class="btn btn__primary" type="submit" name="cancelInvitation">Annuler l'invitation</button>
                                </form>
                                <?php
                            }
                            echo '</ul>';
                        } else {
                            echo '<br /><p>Vous pouvez inviter un joueur à rejoindre votre équipe en utilisant le formulaire ci-dessous.</p>';
                            // formulaire d'invitation pour le joueur restant

                            echo
                            '<form action="" method="post">
                            <input type="email" name="playerToInvite" placeholder="Email du joueur à inviter" required>
                            <input type="hidden" name="teamId" value="' . $playerTeam['TeamId'] . '">
                            <button class="btn btn__primary" type="submit" value="Inviter" name="monoInvitation">Inviter</button>

                            </form>';
                        }
                    } elseif ($number_of_players === 1) {
                        if (!empty($invitations)) {
                            echo
                            '<h4>Invitations en cours :</h4>';

                            if (count($invitations) === 2) {
                                // Toutes les invitations
                                echo '<ul>';
                                foreach ($invitations as $invitation) {
                                    echo '<li>' . $invitation['InvitationEmail'] . ' - ' . $invitation['InvitationStatus'] . '</li>';
                                ?>
                                    <form action="" method="post">
                                        <input type="hidden" name="invitationId" value="<?php echo $invitation['InvitationId']; ?>">
                                        <button class="btn btn__primary" type="submit" name="cancelInvitation">Annuler l'invitation</button>
                                    </form>
                                <?php
                                }
                                echo '</ul>';
                            } elseif (count($invitations) === 1) {
                                // Toutes les invitations
                                echo '<ul>';
                                foreach ($invitations as $invitation) {
                                    echo '<li>' . $invitation['InvitationEmail'] . ' - ' . $invitation['InvitationStatus'] . '</li>';
                                ?>
                                    <form action="" method="post">
                                        <input type="hidden" name="invitationId" value="<?php echo $invitation['InvitationId']; ?>">
                                        <button class="btn btn__primary" type="submit" name="cancelInvitation">Annuler l'invitation</button>
                                    </form>
        <?php
                                }
                                echo '</ul>';

                                echo '<br /><p>Vous pouvez inviter un joueur à rejoindre votre équipe en utilisant le formulaire ci-dessous.</p>';
                                // formulaire d'invitation pour le joueur restant
                                echo
                                '<form action="" method="post">
                                    <input type="email" name="playerToInvite" placeholder="Email du joueur à inviter" required>
                                    <input type="hidden" name="teamId" value="' . $playerTeam['TeamId'] . '">
                                    <button class="btn btn__primary" type="submit" value="Inviter" name="monoInvitation">Inviter</button>

                                </form>';
                            }
                        } else {
                            echo '<p>Vous pouvez inviter deux joueurs à rejoindre votre équipe en utilisant le formulaire ci-dessous.</p>';
                            // formulaire d'invitation pour les deux joueurs restants
                            echo
                            '<form action="" method="post">
                                    <input type="email" name="playerToInvite" placeholder="Email du joueur à inviter" required>
                                    <input type="hidden" name="teamId" value="' . $playerTeam['TeamId'] . '">
                                    <button class="btn btn__primary" type="submit" value="Inviter" name="monoInvitation">Inviter</button>
                                </form>';
                            echo
                            '<form action="" method="post">
                                    <input type="email" name="playerToInvite" placeholder="Email du joueur à inviter" required>
                                    <input type="hidden" name="teamId" value="' . $playerTeam['TeamId'] . '">
                                    <button class="btn btn__primary" type="submit" value="Inviter" name="monoInvitation">Inviter</button>

                                </form>';
                        }
                    }
                }

                // checking POSTS 
                if (isset($_POST['monoInvitation'])) {
                    //Select team id of current team chief
                    $selectTeamId = $conn2->prepare("SELECT * FROM teams, players, appartient WHERE appartient.AppartientPlayerId = players.PlayerId AND teams.TeamId = appartient.AppartientTeamId AND players.PlayerId = ?");
                    $selectTeamId->bindValue(1, $_SESSION['PlayerId']);
                    $selectTeamId->execute();
                    $teamIdResult = $selectTeamId->fetch(PDO::FETCH_ASSOC);

                    if ($_POST['teamId'] === $teamIdResult['AppartientTeamId']) {


                        //génération d'un token
                        $token = generateRandomString();

                        //TODO: vérifier si l'input n'est pas trafiqué et si l'équipe existe bien

                        $query = "SELECT IFNULL(MAX(InvitationId), 0) + 1 as NewInvitationId FROM invitations";
                        $NewInvitationId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment

                        $invite_link = $settings['instance_url'] . '/register.php?JoinId=' . $NewInvitationId['NewInvitationId'] . '&JoinToken=' . $token;
                        $join_link = $settings['instance_url'] . '/login.php?JoinId=' . $NewInvitationId['NewInvitationId'] . '&JoinToken=' . $token;

                        $mail_template_invite = file_get_contents('lib/mail_template_invite.html');
                        $mail_template_invite = str_replace("{[URL_INVITE]}", $invite_link, $mail_template_invite);
                        $mail_template_invite = str_replace("{[MAIL_INVITE]}", $settings['instance_email_support'], $mail_template_invite);
                        $mail_template_invite = str_replace("{[NAME_INVITE]}", $settings['name'], $mail_template_invite);

                        $mail_template_join = file_get_contents('lib/mail_template_invite.html');
                        $mail_template_join = str_replace("{[URL_INVITE]}", $join_link, $mail_template_join);
                        $mail_template_join = str_replace("{[MAIL_INVITE]}", $settings['instance_email_support'], $mail_template_join);
                        $mail_template_join = str_replace("{[NAME_INVITE]}", $settings['name'], $mail_template_join);


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

                            $checkTeam = $conn2->prepare("SELECT *
                                                    FROM teams, players, appartient
                                                    WHERE appartient.AppartientPlayerId = players.PlayerId
                                                    AND teams.TeamId = appartient.AppartientTeamId
                                                    AND players.PlayerEmail = ?
                                                    ");
                            $checkTeam->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                            $checkTeam->execute();
                            $checkTeamResult = $checkTeam->fetch(PDO::FETCH_ASSOC);

                            if (empty($checkTeamResult)) {


                                // check si email est déjà invité
                                $checkInvitation = $conn2->prepare("SELECT *
                                                            FROM invitations
                                                            WHERE invitations.InvitationEmail = ?
                                                            AND invitations.InvitationStatus NOT IN ('denied','accepted', 'cancelled')
                                                            ");
                                $checkInvitation->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                $checkInvitation->execute();
                                $invitation = $checkInvitation->fetch(PDO::FETCH_ASSOC);

                                /* Check si le joueur est déjà chef d'une équipe */
                                $checkCaptain = $conn2->prepare("SELECT *
                                                    FROM appartient, players
                                                    WHERE players.PlayerId = appartient.AppartientPlayerId
                                                    AND players.PlayerEmail = ?
                                                    AND appartient.AppartientRole = 'chef'
                                                    ");
                                $checkCaptain->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                $checkCaptain->execute();
                                $isCaptain = $checkCaptain->fetch(PDO::FETCH_ASSOC);

                                // var_dump($invitation);
                                if (empty($invitation) && empty($isCaptain)) {
                                    // check si email est déjà membre de l'équipe
                                    $checkPlayerOfTeam = $conn2->prepare("SELECT *
                                                                FROM players, teams, appartient
                                                                WHERE players.PlayerId = appartient.AppartientPlayerId
                                                                AND teams.TeamId = appartient.AppartientTeamId
                                                                AND players.PlayerEmail = ?
                                                                AND teams.TeamId = ?");
                                    $checkPlayerOfTeam->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                    $checkPlayerOfTeam->bindValue(2, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                                    $checkPlayerOfTeam->execute();
                                    $isPlayerOfTeam = $checkPlayerOfTeam->fetch(PDO::FETCH_ASSOC);
                                    // var_dump($playerOfTeam);
                                    if (empty($isPlayerOfTeam)) {
                                        echo "<p>Un mail lui sera envoyé pour l'inviter rejoindre votre équipe !</p>";
                                        // envoi mail
                                        $mail = new PHPMailer(true);
                                        try {
                                            $mail->isSMTP();
                                            $mail->Host       = $settings['instance_email_host'];
                                            $mail->SMTPAuth   = true;
                                            $mail->Username   = $settings['instance_email_username'];
                                            $mail->Password   = $settings['instance_email_password'];
                                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                            $mail->Port       = $settings['instance_email_port'];

                                            $mail->Encoding = 'base64';
                                            $mail->CharSet = 'UTF-8';

                                            $mail->setFrom($settings['instance_email_username'], $settings['name']);
                                            $mail->addAddress($_POST['playerToInvite']);
                                            $mail->addReplyTo($settings['instance_email_username'], $settings['name']);

                                            $mail->isHTML(true);
                                            $mail->Subject = 'Invitation à rejoindre une équipe - Evenement MMI LAN';
                                            $mail->Body    = $mail_template_join;
                                            $mail->AltBody = 'Bonjour, vous avez été invité à rejoindre une équipe sur Evenement MMI LAN. Pour accepter l\'invitation, veuillez cliquer sur
                                    le lien suivant : ' . $join_link;

                                            $mail->send();



                                            // enregistrement de l'invitation
                                            $insertInvitation = $conn2->prepare("INSERT INTO invitations (InvitationId,InvitationEmail, InvitationTeamId, InvitationStatus, InvitationToken)
                                                                                                                                    VALUES (?, ?, ?, ?, ?)");
                                            $insertInvitation->bindValue(1, $NewInvitationId['NewInvitationId']);
                                            $insertInvitation->bindValue(2, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                            $insertInvitation->bindValue(3, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                                            $insertInvitation->bindValue(4, 'pending');
                                            $insertInvitation->bindValue(5, $token);
                                            $insertInvitation->execute();

                                            echo '<meta http-equiv="refresh" content="0;url=profile?mailSent=success">';
                                        } catch (Exception $e) {
                                            echo $email_not_sent . "<br><p>Merci de transmettre ces informations à l'administrateur : {$mail->ErrorInfo}</p>";
                                        }
                                    } else {
                                        echo '<p class="error">Ce joueur est déjà membre de votre équipe.</p>';
                                    }
                                } else {
                                    echo '<p class="error">Ce joueur est déjà invité dans une équipe ou est déjà chef d\'une équipe.</p>';
                                }
                            } else {
                                echo '<p class="error">Ce joueur est déjà membre d\'une équipe</p>';
                            }
                        } else {
                            echo "<p>Ce joueur n'a pas encore de compte.</p>";
                            // check si email est déjà invité
                            $checkInvitation = $conn2->prepare("SELECT *
                                                            FROM invitations
                                                            WHERE invitations.InvitationEmail = ?
                                                            AND invitations.InvitationStatus NOT IN ('denied','accepted', 'cancelled')
                                                            ");
                            $checkInvitation->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                            $checkInvitation->execute();
                            $invitation = $checkInvitation->fetch(PDO::FETCH_ASSOC);
                            // var_dump($invitation);
                            if (empty($invitation)) {
                                // check si email est déjà membre de l'équipe
                                $checkPlayerOfTeam = $conn2->prepare("SELECT *
                                                                FROM players, appartient, teams
                                                                WHERE players.PlayerEmail = ?
                                                                and teams.TeamId = ?
                                                                and appartient.AppartientPlayerId = players.PlayerId
                                                                and appartient.AppartientTeamId = teams.TeamId");
                                $checkPlayerOfTeam->bindValue(1, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                $checkPlayerOfTeam->bindValue(2, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                                $checkPlayerOfTeam->execute();
                                $isPlayerOfTeam = $checkPlayerOfTeam->fetch(PDO::FETCH_ASSOC);
                                // var_dump($playerOfTeam);
                                if (empty($isPlayerOfTeam)) {
                                    echo "<p>Un mail lui sera envoyé pour l'inviter à créer un compte.</p>";
                                    // envoi mail
                                    $mail = new PHPMailer(true);
                                    try {
                                        $mail->isSMTP();
                                        $mail->Host       = $settings['instance_email_host'];
                                        $mail->SMTPAuth   = true;
                                        $mail->Username   = $settings['instance_email_username'];
                                        $mail->Password   = $settings['instance_email_password'];
                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                        $mail->Port       = $settings['instance_email_port'];

                                        $mail->Encoding = 'base64';
                                        $mail->CharSet = 'UTF-8';

                                        $mail->setFrom($settings['instance_email_username'], $settings['name']);
                                        $mail->addAddress($_POST['playerToInvite']);
                                        $mail->addReplyTo($settings['instance_email_username'], $settings['name']);

                                        $mail->isHTML(true);
                                        $mail->Subject = 'Invitation à rejoindre une équipe - Evenement MMI LAN';
                                        $mail->Body    = $mail_template_invite;
                                        $mail->AltBody = 'Bonjour, vous avez été invité à rejoindre une équipe sur Evenement MMI LAN. Pour accepter l\'invitation, veuillez cliquer sur
                                    le lien suivant : ' . $invite_link . '. Vous allez pouvoir créer un compte et rejoindre directement l\'équipe.';

                                        $mail->send();



                                        // enregistrement de l'invitation
                                        $insertInvitation = $conn2->prepare("INSERT INTO invitations (InvitationId,InvitationEmail, InvitationTeamId, InvitationStatus, InvitationToken)
                                                                                                                                    VALUES (?, ?, ?, ?, ?)");
                                        $insertInvitation->bindValue(1, $NewInvitationId['NewInvitationId']);
                                        $insertInvitation->bindValue(2, htmlspecialchars($_POST['playerToInvite'], ENT_QUOTES, 'UTF-8'));
                                        $insertInvitation->bindValue(3, htmlspecialchars($_POST['teamId'], ENT_QUOTES, 'UTF-8'));
                                        $insertInvitation->bindValue(4, 'pending');
                                        $insertInvitation->bindValue(5, $token);
                                        $insertInvitation->execute();

                                        echo '<meta http-equiv="refresh" content="0;url=profile?mailSent=success">';
                                    } catch (Exception $e) {
                                        echo $email_not_sent . "<br>Merci de transmettre ces informations à l'administrateur : {$mail->ErrorInfo}";
                                    }
                                } else {
                                    echo '<p>Ce joueur est déjà membre de votre équipe.</p>';
                                }
                            } else {
                                echo '<p>Ce joueur est déjà invité dans une équipe.</p>';
                            }
                        }
                    } else {

                        $query = "SELECT IFNULL(MAX(LogId), 0) + 1 as newLogId FROM logs";
                        $newLogId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment

                        $logHack = $conn2->prepare("INSERT INTO logs (LogId, LogMsg, LogUserMail) VALUES (?,?,?)");
                        $logHack->bindValue(1, $newLogId['newLogId']);
                        $logHack->bindValue(2, "Hack Attempt :" . $_SESSION['PlayerMail'] . " à tenté de modifier un input");
                        $logHack->bindValue(3, $_SESSION['PlayerMail']);
                        $logHack->execute();

                        echo '<p class="error">Merci de ne pas modifier les inputs! Si vous n\'y êtes pour rien, contactez <a href="mailto:mathislambert.dev@gmail.com" style="color:red;">mathislambert.dev@gmail.com</a> </p>';
                    }
                }
            } else { // affichage en tant que membre équipe
                // afficher les membres de l'équipe
                $query = $conn2->prepare("SELECT * 
									FROM players, appartient, teams
									WHERE players.PlayerStatus = 'ok'
									and teams.TeamStatus NOT IN ('banned', 'ban')
									and appartient.AppartientStatus not in ('del','canceled')
                                    and players.PlayerId = appartient.AppartientPlayerId
                                    and appartient.AppartientTeamId = teams.TeamId
                                    and appartient.AppartientTeamId = ?
									");
                $query->bindValue(1, htmlspecialchars($playerTeam["TeamId"], ENT_QUOTES, 'UTF-8'));
                $query->execute();
                $playersOfPlayerTeam = $query->fetchAll(PDO::FETCH_ASSOC); //
                echo '<h3>Membres de votre equipe</h3>';
                foreach ($playersOfPlayerTeam as $playerOfPlayerTeam) {
                    echo '<p>' . $playerOfPlayerTeam['PlayerFirstname'] . ' ' . $playerOfPlayerTeam['PlayerLastname'] . ' (' . $playerOfPlayerTeam['AppartientRole'] . ')</p>';
                }
            }
        } else if (!empty($playerSolo)) {
            // affichage en tant que joueur solo
            echo '<p>Vous êtes inscrit en solo.</p>';
            echo '<form method="post"><button class="btn btn__primary" name="leaveSolo" type="submit">Quitter le mode solo</button></form>';
        } else { // affichage en tant que joueur non inscrit
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

                    //vérifier que le nom de l'équipe n'est pas déjà utilisé
                    $checkTeamName = $conn2->prepare("SELECT * FROM teams WHERE teams.TeamName = ?");
                    $checkTeamName->bindValue(1, htmlspecialchars($_POST['TeamName'], ENT_QUOTES, 'UTF-8'));
                    $checkTeamName->execute();
                    $teamName = $checkTeamName->fetch(PDO::FETCH_ASSOC);

                    if (empty($teamName)) { // le nom de l'équipe n'est pas déjà utilisé
                        // enregistrement de l'équipe
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

                        $query = "SELECT IFNULL(MAX(AppartientId), 0) + 1 as NewAppartientId FROM appartient";
                        $NewAppartientId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment;

                        $query = $conn2->prepare("INSERT INTO appartient 
                                    (AppartientId, AppartientPlayerId, AppartientTeamId, AppartientRole)
                                    VALUES (?, ?, ?, 'chef')
                                  ");
                        $query->bindValue(1, $NewAppartientId["NewAppartientId"]);
                        $query->bindValue(2, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                        $query->bindValue(3, $newTeamId["NewTeamId"]);
                        $query->execute();

                        echo ' <form method="post" id="refresher" ><input type="hidden" name="msg" value="successfullAddedTeam"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
                        die();
                    } else {
                        echo '<p>Le nom de l\'équipe est déjà utilisé. Veuillez en choisir un différent</p>';
                    }
                }
            } elseif (isset($_POST['select']) and in_array($_POST['select'], ['team', 'solo'])) { // si le bouton solo ou chef d'équipe a été cliqué

                if ($_POST['select'] === "team") { // si le bouton chef d'équipe a été cliqué
                    echo '
                        <form method="post">
                            <button type="submit" class="N_button">Retour</button>
                        </form>
                        ';
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
                    $checkSolo = $conn2->prepare("select * from appartient_solo where AppartientSoloPlayerId = ?");
                    $checkSolo->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                    $checkSolo->execute();
                    $checkSoloResult = $checkSolo->fetch(PDO::FETCH_ASSOC);

                    if (empty($checkSoloResult)) {
                        // s'inscrire en tant que joueur solo
                        // Check if the player with a pending invitation
                        $checkInvitation = $conn2->prepare("select * from invitations where InvitationEmail = ?");
                        $checkInvitation->bindValue(1, htmlspecialchars($_SESSION["PlayerMail"], ENT_QUOTES, 'UTF-8'));
                        $checkInvitation->execute();
                        $checkInvitationResult = $checkInvitation->fetch(PDO::FETCH_ASSOC);

                        if (!empty($checkInvitationResult)) {
                            // If the player has a pending invitation, ask him/her if he/she really wants to cancel it
                            //Select team name from invitation
                            $query = $conn2->prepare("SELECT teams.TeamName FROM teams, invitations WHERE invitations.InvitationTeamId = teams.TeamId AND invitations.InvitationEmail = ?");
                            $query->bindValue(1, htmlspecialchars($_SESSION["PlayerMail"], ENT_QUOTES, 'UTF-8'));
                            $query->execute();
                            $teamName = $query->fetch(PDO::FETCH_ASSOC);

                            echo '<div class="alert">Vous avez une invitation en attente de la part de ' . $teamName['TeamName'] . '. Si vous vous inscrivez en solo, celle-ci sera annulée.</div>';
                            echo '
                            <form action="" method="post">
                                <button type="submit" class="btn btn__primary" name="validateJoinSolo">Confirmer</button>
                                <button type="submit" class="btn btn__secondary ">Annuler</button>
                            </form>
                            ';
                        } else {
                            // If the player has no pending invitation, he/she can register as a solo player
                            echo '
                            <form action="" method="post">
                                <button type="submit" class="btn btn__primary" name="validationSolo">Confirmer</button>
                                <button type="submit" class="btn btn__secondary ">Annuler</button>
                            </form>
                            ';
                        }
                    } else if ($checkSoloResult['AppartientSoloStatus'] == "ok") {
                        echo "Vous êtes déjà inscrit en solo";
                    } else if ($checkSoloResult['AppartientSoloStatus'] == "old") {
                        // s'inscrire en tant que joueur solo
                        // Check if the player with a pending invitation
                        $checkInvitation = $conn2->prepare("select * from invitations where InvitationEmail = ? and InvitationStatus = 'pending'");
                        $checkInvitation->bindValue(1, htmlspecialchars($_SESSION["PlayerMail"], ENT_QUOTES, 'UTF-8'));
                        $checkInvitation->execute();
                        $checkInvitationResult = $checkInvitation->fetch(PDO::FETCH_ASSOC);

                        if (!empty($checkInvitationResult)) {
                            // If the player has a pending invitation, ask him/her if he/she really wants to cancel it
                            //Select team name from invitation
                            $query = $conn2->prepare("SELECT teams.TeamName FROM teams, invitations WHERE invitations.InvitationTeamId = teams.TeamId AND invitations.InvitationEmail = ?");
                            $query->bindValue(1, htmlspecialchars($_SESSION["PlayerMail"], ENT_QUOTES, 'UTF-8'));
                            $query->execute();
                            $teamName = $query->fetch(PDO::FETCH_ASSOC);

                            echo '<div class="alert">Vous avez une invitation en attente de la part de ' . $teamName['TeamName'] . '. Si vous vous inscrivez en solo, celle-ci sera annulée.</div>';
                            echo '
                            <form action="" method="post">
                                <button type="submit" class="btn btn__primary" name="validateJoinSolo">Confirmer</button>
                                <button type="submit" class="btn btn__secondary ">Annuler</button>
                            </form>
                            ';
                        } else {
                            // se réinscrire en tant que joueur solo
                            $joinSolo = $conn2->prepare("UPDATE appartient_solo SET AppartientSoloStatus = 'ok' WHERE AppartientSoloPlayerId = ?");
                            $joinSolo->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                            $joinSolo->execute();
                            echo "Vous êtes maintenant à nouveau inscrit en solo, la page va se recharger.";
                            // reload page
                            echo '<meta http-equiv="refresh" content="0;url=profile?success=vous-êtes-inscrit-en-solo">';
                        }
                    }
                }
            } else { // aucune équipe
                //vérifier en bdd si une invitation a été adressée au joueur connecté, possibilité d'accepter ou refuser = changer statut de l'invitation en bdd, et de rejoindre, ou pas, l'équipe
                echo '
        <form method="post">
            <input type="hidden" name="select" value="team">
            <button  class="btn btn__primary" type="submit">Je crée une équipe</button>
        </form>
        ';
                echo '
        <form method="post">
            <input type="hidden" name="select" value="solo">
            <button class="btn btn__primary"  type="submit">Je suis solo pour le moment</button>
        </form>
        ';
            }
            //* Si le bouton de validation de l'inscription solo est submit
            if (isset($_POST["validationSolo"])) {
                // se réinscrire en tant que joueur solo
                $joinSolo = $conn2->prepare("UPDATE appartient_solo SET AppartientSoloStatus = 'ok' WHERE AppartientSoloPlayerId = ?");
                $joinSolo->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                $joinSolo->execute();

                echo "Vous êtes maintenant à nouveau inscrit en solo, la page va se recharger.";
                // reload page
                echo '<meta http-equiv="refresh" content="0;url=profile?success=vous-êtes-inscrit-en-solo">';
            }
            if (isset($_POST['validateJoinSolo'])) {
                // Check if the player with a pending invitation
                $checkInvitation = $conn2->prepare("SELECT * from invitations where invitations.InvitationEmail = ? AND invitations.InvitationStatus = 'pending'");
                $checkInvitation->bindValue(1, htmlspecialchars($_SESSION["PlayerMail"], ENT_QUOTES, 'UTF-8'));
                $checkInvitation->execute();
                $checkInvitationResult = $checkInvitation->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($checkInvitationResult)) {

                    // cancel invitation and join solo
                    $cancelInvitation = $conn2->prepare("UPDATE invitations SET invitations.InvitationStatus = 'denied' WHERE InvitationEmail = ?");
                    $cancelInvitation->bindValue(1, htmlspecialchars($_SESSION["PlayerMail"], ENT_QUOTES, 'UTF-8'));
                    $cancelInvitation->execute();

                    $checkSolo = $conn2->prepare("select * from appartient_solo where AppartientSoloPlayerId = ?");
                    $checkSolo->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                    $checkSolo->execute();
                    $checkSoloResult = $checkSolo->fetch(PDO::FETCH_ASSOC);

                    if (!empty($checkSoloResult)) {
                        $joinSolo = $conn2->prepare("UPDATE appartient_solo SET AppartientSoloStatus = 'ok' WHERE AppartientSoloPlayerId = ?");
                        $joinSolo->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                        $joinSolo->execute();
                        echo "Vous êtes maintenant à nouveau inscrit en solo, la page va se recharger.";
                        // reload page
                        echo '<meta http-equiv="refresh" content="0;url=profile?success=vous-êtes-inscrit-en-solo">';
                    } else {
                        $query = "SELECT IFNULL(MAX(AppartientSoloId), 0) + 1 as NewAppartientSoloId FROM appartient_solo";
                        $NewAppartientSoloId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment;

                        $joinSolo = $conn2->prepare("INSERT INTO appartient_solo (AppartientSoloId, AppartientSoloPlayerId, AppartientSoloStatus) VALUES (?, ?, 'ok')");
                        $joinSolo->bindValue(1, $NewAppartientSoloId['NewAppartientSoloId']);
                        $joinSolo->bindValue(2, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                        $joinSolo->execute();
                        echo "Vous êtes maintenant inscrit en solo, la page va se recharger.";
                        // reload page
                        echo '<meta http-equiv="refresh" content="0;url=profile?success=vous-êtes-inscrit-en-solo">';
                    }
                } else {
                    // join solo
                    $query = "SELECT IFNULL(MAX(AppartientSoloId), 0) + 1 as NewAppartientSoloId FROM appartient_solo";
                    $NewAppartientSoloId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment;

                    $joinSolo = $conn2->prepare("INSERT INTO appartient_solo (AppartientSoloId, AppartientSoloPlayerId, AppartientSoloStatus) VALUES (?, ?, 'ok')");
                    $joinSolo->bindValue(1, $NewAppartientSoloId['NewAppartientSoloId']);
                    $joinSolo->bindValue(2, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
                    $joinSolo->execute();
                    echo "Vous êtes maintenant en tant que joueur solo, la page va se recharger.";
                    // reload page
                    echo '<meta http-equiv="refresh" content="0;url=profile?success=vous-êtes-inscrit-en-solo">';
                }
            }
        }
        //check invitations of the player
        $checkInvitation = $conn2->prepare("SELECT *
                                                FROM players, invitations, teams
                                                WHERE players.PlayerStatus = 'ok'
                                                and teams.TeamStatus NOT IN ('banned', 'ban')
                                                and invitations.InvitationStatus not in ('denied','accepted', 'cancelled')
                                                and invitations.InvitationTeamId = teams.TeamId
                                                and invitations.InvitationEmail = players.PlayerEmail
                                                and invitations.InvitationEmail = ?
                                                ");
        $checkInvitation->bindValue(1, htmlspecialchars($_SESSION['PlayerMail'], ENT_QUOTES, 'UTF-8'));
        $checkInvitation->execute();
        $invitation = $checkInvitation->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($invitation)) {
            echo "<h1> Vos invitations </h1> <br />";
            // si invitation en cours, afficher statut
            echo '<p>Vous avez une invitation en attente.</p><br />';
            echo '<ul>';
            echo '<li><h3>' . $invitation[0]['TeamName'] . ' - ' . $invitation[0]['InvitationStatus'] . '</h3></li>';
            echo '</ul>';
            echo '<form action="" method="post">
                        <input type="hidden" name="invitationId" value="' . $invitation[0]['InvitationId'] . '">
                        <button type="submit"  class="btn btn__light" name="acceptInvitation">Accepter</button>
                        <button type="submit"  class="btn btn__light" name="refuseInvitation">Refuser</button>
                    </form>';
        }

        if (isset($_GET['mailSent']) && $_GET['mailSent'] == 'success') {
            echo $email_sent;
            //delete GET
            $_GET['mailSent'] = null;
        }

        ?>

        <div class="success_msg">
            <script>
                let getSuccess = new URLSearchParams(window.location.search);
                if (getSuccess.has('success')) {
                    document.querySelector('.success_msg').innerHTML += `<p class="success">${getSuccess.get('success').split('-').join(' ')}</p>`;
                }
                document.addEventListener("click", () => {
                    document.querySelector('.success_msg').innerHTML = '';
                });
            </script>
        </div>

    </div>
</body>

</html>