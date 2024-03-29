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
       <title>Se connecter</title>
       <?php
        include_once './includes/head.php';
        ?>
       <link rel="stylesheet" href="css/connexion.css" />

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
        $need_account = (isset($_GET['msg']) and $_GET['msg'] === "needAccount");
        $blocked = (isset($_GET['msg']) and $_GET['msg'] === "blocked");
        $passwordEdited = (isset($_GET['msg']) and $_GET['msg'] === "passwordEdited");
        $accountCreated = (isset($_GET['msg']) and $_GET['msg'] === "accountCreated");

        session_start();
        define('MyConst', TRUE);
        require('app/config.php');

        if (isset($_SESSION["PlayerId"])) {
            header("Location: profile.php");
            exit();
        }

        if (((isset($_POST['MailUtilisateur']) and !empty($_POST['MailUtilisateur'])))  and  (isset($_POST['MdpUtilisateur']) and !empty($_POST['MdpUtilisateur']))) {
            $query = $conn2->prepare("SELECT * 
									FROM players
									WHERE players.PlayerStatus = 'ok'
									and players.PlayerEmail = ?");
            $query->bindValue(1, htmlspecialchars($_POST['MailUtilisateur'], ENT_QUOTES, 'UTF-8'));
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (isset($result[0])) {
                $result = $result[0];
                $date_hier = date_create(date('Y-m-d H:i:s'))->modify('-1 days')->format('Y-m-d H:i:s');

                $query = $conn2->prepare("SELECT count(*) as NombreTentatives
					FROM players, tentative
					WHERE players.PlayerId = tentative.PlayerId
					and players.PlayerId = ?
					and tentative.DateTentative > ?
					and tentative.StatusTentative = 'new'");
                $query->bindValue(1, htmlspecialchars($result['PlayerId'], ENT_QUOTES, 'UTF-8'));
                $query->bindValue(2, $date_hier);
                $query->execute();
                $NombreTentatives = $query->fetchAll(PDO::FETCH_ASSOC);

                if (isset($NombreTentatives[0]['NombreTentatives']) and $NombreTentatives[0]['NombreTentatives'] < 5) {
                    if (password_verify($_POST['MdpUtilisateur'], $result['PlayerPassword'])) {  //correct password => login
                        if (isset($_POST['JoinId']) and isset($_POST['JoinToken'])) {
                            // check if the invitation exists
                            $checkInvitationStatus = $conn2->prepare("SELECT *
                        FROM invitations 
                        WHERE InvitationId = ? and InvitationToken = ?");
                            $checkInvitationStatus->bindValue(1, htmlspecialchars($_POST['JoinId'], ENT_QUOTES, 'UTF-8'));
                            $checkInvitationStatus->bindValue(2, htmlspecialchars($_POST['JoinToken'], ENT_QUOTES, 'UTF-8'));
                            $checkInvitationStatus->execute();
                            $resultInvitationStatus = $checkInvitationStatus->fetchAll(PDO::FETCH_ASSOC);

                            if ($resultInvitationStatus[0]['InvitationStatus'] != 'pending') {
                                header('Location: login.php?error=Cette-invitation-n\'est-plus-valide');
                            } else if ($resultInvitationStatus[0]['InvitationEmail'] != $result['PlayerEmail']) {
                                header('Location: login.php?error=Cette-invitation-n\'est-pas-pour-vous');
                            } else if ($resultInvitationStatus[0]['InvitationStatus'] == 'pending') {
                                $_SESSION["PlayerId"] = $result['PlayerId'];
                                $_SESSION["PlayerMail"] = $result['PlayerEmail'];

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
                                    $setOld->bindValue(
                                        1,
                                        htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8')
                                    );
                                    $setOld->execute();
                                }

                                $query = $conn2->prepare("UPDATE tentative SET tentative.StatusTentative = 'old' WHERE tentative.PlayerId = ?  ");
                                $query->bindValue(1, $result['PlayerId']);
                                $query->execute(); // delete tentatives of the user logged

                                $getTeamId = $conn2->prepare("SELECT InvitationTeamId
                        FROM invitations 
                        WHERE InvitationId = ? and InvitationToken = ?");
                                $getTeamId->bindValue(1, htmlspecialchars($_POST['JoinId'], ENT_QUOTES, 'UTF-8'));
                                $getTeamId->bindValue(2, htmlspecialchars($_POST['JoinToken'], ENT_QUOTES, 'UTF-8'));
                                $getTeamId->execute();
                                $resultTeamId = $getTeamId->fetchAll(PDO::FETCH_ASSOC);


                                $query = "SELECT IFNULL(MAX(AppartientId), 0) + 1 as NewAppartientId FROM appartient";
                                $NewAppartientId = $conn2->query($query)->fetch(); // look for the highest number of TeamId and add 1. ==> Home-made Auto-Increment;

                                $query = $conn2->prepare("UPDATE invitations
                                            SET InvitationStatus = 'accepted'
                                            WHERE InvitationId = ?
                                            AND InvitationToken = ?");

                                $query->bindValue(1, htmlspecialchars($_POST['JoinId'], ENT_QUOTES, 'UTF-8'));
                                $query->bindValue(2, htmlspecialchars($_POST['JoinToken'], ENT_QUOTES, 'UTF-8'));
                                $query->execute();

                                $query = $conn2->prepare("INSERT INTO appartient (AppartientId,AppartientPlayerId, AppartientTeamId, AppartientRole)
                                            VALUES (?, ?, ?, 'player')
                                            ");
                                $query->bindValue(1, $NewAppartientId['NewAppartientId']);
                                $query->bindValue(2, $_SESSION["PlayerId"]);
                                $query->bindValue(3, $resultTeamId[0]['InvitationTeamId']);
                                $query->execute();
                                header('Location: profile.php?msg=joined');
                            } else {
                                header('Location: login.php?error=Ce-lien-ne-vous-est-pas-destiné');
                            }
                        } else {
                            $_SESSION["PlayerId"] = $result['PlayerId'];
                            $_SESSION["PlayerMail"] = $result['PlayerEmail'];

                            $query = $conn2->prepare("UPDATE tentative SET tentative.StatusTentative = 'old' WHERE tentative.PlayerId = ?  ");
                            $query->bindValue(1, $result['PlayerId']);
                            $query->execute(); // delete tentatives of the user logged
                            header('Location: profile');
                        }
                    } else {
                        $query = $conn2->prepare("INSERT INTO tentative (CodeTentative, DateTentative, LibTentative, PlayerId) 
											VALUES (NULL, ?, ?, ?) ");
                        $query->bindValue(1, date('Y-m-d H:i:s'));
                        if (password_verify($_POST['MdpUtilisateur'], $result['PlayerPassword'])) {
                            $query->bindValue(2, '/!\\ Real Password /!\\');
                        } else {
                            $query->bindValue(2, base64_encode(htmlspecialchars($_POST['MdpUtilisateur'], ENT_QUOTES, 'UTF-8')));
                        }
                        $query->bindValue(3, $result['PlayerId']);
                        $query->execute(); // insert tentative
                        $result = $query->fetchAll(PDO::FETCH_ASSOC);
                        $generated_id = generateRandomString(5);
                        echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')">Mot de passe incorrect <script> hideIt("modal_' . $generated_id . '",2000); </script> </div>';
                    }
                } else {
                    $query = $conn2->prepare("INSERT INTO tentative (CodeTentative, DateTentative, LibTentative, PlayerId) 
											VALUES (NULL, ?, ?, ?) ");
                    $query->bindValue(1, date('Y-m-d H:i:s'));
                    if (password_verify($_POST['MdpUtilisateur'], $result['PlayerPassword'])) {
                        $query->bindValue(2, '/!\\ Real Password /!\\');
                    } else {
                        $query->bindValue(2, base64_encode(htmlspecialchars($_POST['MdpUtilisateur'], ENT_QUOTES, 'UTF-8')));
                    }
                    $query->bindValue(3, $result['PlayerId']);
                    $query->execute(); // insert tentative
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    $generated_id = generateRandomString(5);
                    echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')" >Trop de tentatives effectuÃ©es. Merci de rÃ©essayez plus tard <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
                }
            } else {
                $generated_id = generateRandomString(5);
                echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')" > Ce compte est inexistant ou a Ã©tÃ© bloquÃ© / supprimÃ©  <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
            }
        } else {
            if (!empty($_POST)) {
                $generated_id = generateRandomString(5);
                echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Merci de remplir tous les champs <script> hideIt("modal_' . $generated_id . '"); </script> </div>';
            }
        }
        echo '<style> body{ background: var(--color-dark) !important;}</style>';
        require('menu.php');

        if ($need_account) {
            $generated_id = generateRandomString(5);
            echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Vous devez d\'abord vous connecter ou crÃ©er un compte. <script> hideIt("modal_' . $generated_id . '",5000); </script> </div>';
        }
        if ($blocked) {
            $generated_id = generateRandomString(5);
            echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Vous n\'Ãªtes pas autorisÃ© Ã  modifier le code source. Votre compte a Ã©tÃ© restreint. <script> hideIt("modal_' . $generated_id . '",5000); </script> </div>';
        }
        if ($passwordEdited) {
            $generated_id = generateRandomString(5);
            echo '<div class="modal success" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Mot de passe modifiÃ© avec succÃ¨s. <script> hideIt("modal_' . $generated_id . '",5000); </script> </div>';
        }
        if ($accountCreated) {
            $generated_id = generateRandomString(5);
            echo '<div class="modal success" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')"> Compte crÃ©Ã© avec succÃ¨s. <script> hideIt("modal_' . $generated_id . '",5000); </script> </div>';
        }
        ?>


       <section id="connexion">
           <div class="tgl1">
               <img src="Elements/others/TriangleJB.svg" alt="Triangle Blanc & Jaune" />
           </div>

           <form method="post" class="form" onsubmit="active_loader()">
               <h2>Connexion</h2>
               <div id="error_container" class="error" style="display: none;"></div>
               <div class="input-group">
                   <input type="email" pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" id="mail_input" name="MailUtilisateur" placeholder=" " autocomplete="current-mail" required>
                   <label for="mail_input">Adresse email</label>
               </div>
               <div class="input-group">
                   <input type="password" id="mdp_input" name="MdpUtilisateur" autocomplete="current-password" placeholder=" " required>
                   <label for="mdp_input">Mot de passe</label>
               </div>
               <a href="recover.php" class="more_txt " onclick="active_loader(); ">Mot de passe oublié ? </a>
               <?php if ($redirect_join) { // si l'url contient le token d'équipe
                    echo '<input type="hidden" name="JoinId" value="' . $_GET['JoinId'] . '">';
                    echo '<input type="hidden" name="JoinToken" value="' . $_GET['JoinToken'] . '">';
                } ?>
               <button class="btn btn__primary" type="submit">valider</button>

           </form>

       </section>

   </body>

   </html>