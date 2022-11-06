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
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
       <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
       <link rel="stylesheet" href="css/connexion.css" />
       <script src="js/main_script.js"></script>
       <link rel="stylesheet" href="css/loader.css" />
       <link rel="stylesheet" href="css/mediaCss.css" />
       <link rel="stylesheet" href="uikit/style/ui-kit.css" />
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
                        $_SESSION["PlayerId"] = $result['PlayerId'];
                        $_SESSION["PlayerMail"] = $result['PlayerEmail'];

                        $query = $conn2->prepare("UPDATE tentative SET tentative.StatusTentative = 'old' WHERE tentative.PlayerId = ?  ");
                        $query->bindValue(1, $result['PlayerId']);
                        $query->execute(); // delete tentatives of the user logged
                        header("location: index.php");
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



       <div style="width: 100%;">
           <h1 class="head_title git pushsecondary">Connexion </h1>
       </div>>

       <div style="display: bloc; height: 70vh; flex-wrap: wrap; align-items: center; justify-content: center; align-content: flex-start;">

           <form method="post" class="form" style="width: 80%;max-width: 380px; margin:0 auto !important; " onsubmit="active_loader()">
               <div class="input-group">
                   <input type="email" pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" id="mail_input" name="MailUtilisateur" style="width:100%" placeholder="Adresse Email" autocomplete="current-mail" required>
               </div>
               <div class="input-group">
                   <input type="password" name="MdpUtilisateur" style="width:100%" autocomplete="current-password" placeholder="Mot de passe " required>
               </div>
               <a href="recover.php" class="more_txt " onclick="active_loader(); ">Mot de passe oublié ? </a>
               <?php if ($redirect_join) { // si l'url contient le token d'équipe
                    echo '<input type="hidden" name="JoinId" value="' . $_GET['JoinId'] . '">';
                    echo '<input type="hidden" name="JoinToken" value="' . $_GET['JoinToken'] . '">';
                } ?>
               <input class="btn btn__primary" type="submit" style="font-weight:bold" value="valider">

               <?php if ($redirect_join) {
                    echo '<a href="register.php?JoinToken=' . htmlspecialchars($_GET['JoinToken'], ENT_QUOTES, 'UTF-8') . '&JoinId=' . htmlspecialchars($_GET['JoinId'], ENT_QUOTES, 'UTF-8') . '" class="links_txt " onclick="active_loader(); ">';
                } else {
                    echo '<a href="register.php" class="links_txt " onclick="active_loader(); ">';
                } ?>
               Inscription ?
               </a>

           </form>
       </div>


   </body>

   </html>