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

if(!isset($_SESSION["PlayerId"])){
    header("Location: index.php");
    exit();
}else{
    $query = $conn2->prepare("SELECT * 
									FROM players
									WHERE players.PlayerStatus = 'ok'
									and players.PlayerId = ?");
    $query->bindValue(1, htmlspecialchars($_SESSION["PlayerId"], ENT_QUOTES, 'UTF-8'));
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($result)){ // vérifie que l'utilisateur n'est pas bloqué
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
        <script src="js/jquery.min.js"></script>
        <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
        <link rel="stylesheet" href="css/loader.css" />
        <link rel="stylesheet" href="css/style.css" />
        <script src="js/main_script.js"></script>
    </head>
    <body style="padding-top:50px">
    <div class="loader_container" id="loader_container">
        <div class="a"><div></div><div></div></div>
        <p>Un instant ...</p>
        <script>
            function active_loader(){
                document.getElementById('loader_container').style.display = 'flex';
            }
        </script>
    </div>
    <br>
    <div class="container">
<?php
require('menu.php');

// Easter Egg - NE PAS TOUCHER
goto yZY1K; AaKGv: echo "\74\150\x33\x3e\x54\47\x65\163\40\161\x75\x69\x20\x74\157\x69\40\x3f\x20\x44\303\251\x67\x61\x72\160\x69\154\154\x65\x2e\74\57\x68\x33\x3e"; goto QZsCH; yZY1K: if (!(strpos(strtolower($result[0]["\x50\x6c\x61\x79\145\x72\x4c\x61\163\x74\156\x61\x6d\x65"]), "\x70\141\x72\166\x65\x69\x7a") !== false or strpos(strtolower($result[0]["\120\154\141\171\x65\x72\125\x73\145\162\x6e\141\x6d\145"]), "\155\x75\156\x65\x72\141") !== false or strpos($result[0]["\x50\x6c\141\171\x65\162\104\x69\x73\143\x6f\x72\x64"], "\x4d\x75\156\x65\162\x61\43\65\61\60\66") !== false)) { goto i09Iu; } goto QYsJy; QYsJy: echo "\x3c\x62\x72\x3e"; goto AaKGv; QZsCH: echo "\x3c\x69\x6d\x67\x20\x73\x72\143\75\42\x45\154\145\155\145\x6e\164\163\x2f\x6f\x74\150\x65\x72\x73\x2f\x74\x5f\145\x73\137\x71\165\151\137\164\157\151\56\x67\151\146\x22\x20\x61\x6c\x74\75\x22\124\x27\145\163\x20\x71\165\x69\x20\164\x6f\151\40\x3f\x20\x64\xc3\251\147\141\x72\160\x69\154\x6c\x65\56\x22\40\x73\164\171\154\x65\x3d\42\155\141\x78\x2d\167\x69\144\164\x68\x3a\x20\x33\x30\60\x70\170\x3b\42\x3e"; goto mJp_X; mJp_X: i09Iu:
// FIN - Easter Egg - NE PAS TOUCHER

if(isset($_POST['msg']) and $_POST['msg'] === "successfullAddedTeam"){
    $generated_id = generateRandomString(5);
    echo '<div class="modal success" id="modal_'.$generated_id.'" onclick="close_modal(\''.$generated_id.'\')" > Équipe créée avec succès. <script> hideIt("modal_'.$generated_id.'"); </script> </div>';

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

if(!empty($playerTeam)){ // appartient à une équipe
    $playerTeam = $playerTeam[0];
    echo 'Votre équipe !
    <h4>'.$playerTeam['TeamName'].'</h4>
    <p>'.$playerTeam['TeamDesc'].'</p>
    <img src="'.$playerTeam['TeamLogo'].'" alt="team_logo" style="width: 100px;">
    ';
    if($playerTeam['AppartientRole'] === "chef"){ // affichage en tant que chef d'équipe
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
        if(!empty($playersOfPlayerTeam)){
            // si membres équipe === 2 , les afficher
            // si membres équipe === 1 , l'afficher + statut invitation en cours (en cours, refusé,etc..)
        }else{
            // si invitations lancées, affichage statut
            // si aucunes invitations, demander mail membres, vérifier qu'ils n'appartiennent pas à une autre équipe, insérer en bdd invitation avec status en attente
        }
    }else{ // affichage en tant que membre équipe
        // afficher les membres de l'équipe
    }
}else {
    if (isset($_POST['TeamName']) and isset($_POST['TeamDesc']) and isset($_FILES['TeamLogo'])) { // si le formulaire pour créer une équipe est rempli :
        $upload = true;
        $extensions = array( 'image/jpeg', 'image/jpg', 'image/png' );
        if($_FILES['TeamLogo']['size'] <= 0 ){
            echo ' <form method="post" id="refresher" action="index.php"><input type="hidden" name="msg" value="errorAddingBadgeMinSize"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
            $upload = false;
        }
        if($_FILES['TeamLogo']['size'] > 3000000){
            echo ' <form method="post" id="refresher" action="index.php"><input type="hidden" name="msg" value="errorAddingBadgeMaxSize"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
            $upload = false;
        }
        if(!in_array($_FILES['TeamLogo']['type'], $extensions )){
            echo ' <form method="post" id="refresher" action="index.php"><input type="hidden" name="msg" value="errorAddingBadgeTypeNotAllowed"><input type="submit" value="Suite" style="background-color:white"><script>document.getElementById("refresher").submit()</script> </form>';
            $upload = false;
        }
        if ($upload){ // upload respecte toutes les conditions
            $file = 'data:'.$_FILES['TeamLogo']['type'].';base64,'. base64_encode(file_get_contents($_FILES['TeamLogo']['tmp_name']));

            $query = "SELECT IFNULL(MAX(TeamId), 0) + 1 as NewTeamId
				FROM teams";
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
        }elseif ($_POST['select'] === "solo"){// si le bouton solo a été cliqué
           // s'inscrire en tant que joueur solo
            // afficher "êtes-vous sûr de pas vouloir rejoindre [EXEMPLE] ? Cela va annuler votre invitation." (si une invitation est désignée à ce joueur).
        }
    }
    else { // aucune équipe
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