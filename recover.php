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
	<title> Récupération </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/loader.css" />
	<script src="js/main_script.js"></script>
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
	define('MyConst', TRUE);
	require('app/config.php');
	session_start();


	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'lib/PHPMailer/src/Exception.php';
	require 'lib/PHPMailer/src/PHPMailer.php';
	require 'lib/PHPMailer/src/SMTP.php';

	$email_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/valid_check.gif" style="width:150px">
            <h3>Demande de réinitialisation de mot de passe envoyée</h3>
            <p>Si un compte est associé à l\'adresse renseignée, une demande de réinitialisation va être envoyée dans les 5 minutes qui suivent. N\'oubliez pas de vérifier le dossier SPAM. </p>
       </div> <br><br><br>';
	$email_not_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/invalid_cross.gif" style="width:150px">
            <h3>Erreur d\'envoi</h3>
            <p>Une erreur s\'est produite lors de l\'envoi du mail de réinitialisation. </p>

       </div> <br><br><br>';
	$invalid_email_or_already_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/invalid_cross.gif" style="width:150px">
            <h3>Erreur d\'envoi</h3>
            <p>Il semblerai qu\'aucun compte n\'est associé à cette adresse mail. <br>Ou une réinitialisation esr déjà en cours. </p>

       </div> <br><br><br>';
	$expired_recover = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
            <img src="Elements/icons/invalid_cross.gif" style="width:150px">
            <h3>Demande expirée</h3>
            <p>Votre demande de réinitialisation a expiré. Veuillez réitérer votre demande </p>

       </div> <br><br><br>';


	if ((isset($_POST['RecoverToken']) and !empty($_POST['RecoverToken'])) and (isset($_POST['RecoverCode']) and !empty($_POST['RecoverCode'])) and (isset($_POST['RecoverNewMdp']) and !empty($_POST['RecoverNewMdp']))) {
		echo '
<div style="display: flex; flex-direction: column;    padding: 0px 50px; gap:15px;">';
		$query = $conn2->prepare("SELECT count(*) as NombreReinitialisation
							FROM reinitialisation 
							WHERE reinitialisation.TokenReinitialisation = ? 
							and reinitialisation.PlayerId = ? ");
		$query->bindValue(1, $_POST['RecoverToken']);
		$query->bindValue(2, $_POST['RecoverCode']);
		$query->execute(); //looks if the reinitialisation asked exist
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (isset($result[0])) {
			$result = $result[0];
			if ($result['NombreReinitialisation'] == 1) {
				$query = $conn2->prepare("UPDATE players 
									SET PlayerPassword = ? 
									WHERE players.PlayerId = ?");
				$query->bindValue(1, password_hash($_POST['RecoverNewMdp'], PASSWORD_DEFAULT));
				$query->bindValue(2, $_POST['RecoverCode']);
				$query->execute(); // update the password with the new one


				$query = $conn2->prepare("DELETE FROM reinitialisation 
									WHERE reinitialisation.TokenReinitialisation = ? 
									and reinitialisation.PlayerId = ? ");
				$query->bindValue(1, $_POST['RecoverToken']);
				$query->bindValue(2, $_POST['RecoverCode']);
				$query->execute(); // delete tentatives of the user logged

				header("Location: login.php?msg=passwordEdited");
			}
		}
		require('menu.php');
		$generated_id = generateRandomString(5);
		echo '<div class="modal error" id="modal_' . $generated_id . '" onclick="close_modal(\'' . $generated_id . '\')" > Ce compte ne vous appartient pas.<script> hideIt("modal_' . $generated_id . '"); </script> </div>';
		exit();
	}

	if ((isset($_GET['RecoverToken']) and !empty($_GET['RecoverToken'])) and (isset($_GET['RecoverCode']) and !empty($_GET['RecoverCode']))) {
		require('menu.php');
		echo '<br><br><br><br>';
		echo '
<div style="display: flex; flex-direction: column;    padding: 0px 50px; gap:15px;">';
		$date_il_y_a_quinze_min = date_create(date('Y-m-d H:i:s'))->modify('-15 minutes')->format('Y-m-d H:i:s');
		$query = $conn2->prepare("SELECT *
							FROM reinitialisation 
							WHERE reinitialisation.TokenReinitialisation = ? 
							and reinitialisation.PlayerId = ? 
							and reinitialisation.DateReinitialisation > ? ");
		$query->bindValue(1, $_GET['RecoverToken']);
		$query->bindValue(2, $_GET['RecoverCode']);
		$query->bindValue(3, $date_il_y_a_quinze_min);
		$query->execute(); // looks if a reinitialisation asked exists with this token and this user
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		if (isset($result[0])) {
			$result = $result[0];
			echo '
		<form method="post" style="display:flex;flex-direction:column;gap:15px" onsubmit="active_loader(); ">
			 <h3 class="box-title">Réinitialisation du mot de passe</h3>
			<input type="password" name="RecoverNewMdp" placeholder="Nouveau Mot de Passe" autocomplete="new-password" required>
			<input type="hidden" name="RecoverToken" required value="' . $_GET['RecoverToken'] . '">
			<input type="hidden" name="RecoverCode" required value="' . $_GET['RecoverCode'] . '">
			<input type="submit" value="Réinitialiser" class="box-button ">
			
		</form>
	
		';
			die();
		} else {
			echo $expired_recover;
		}
	}
	if (isset($_SESSION["PlayerId"])) {
		header("Location: index.php");
	} else {
		require('menu.php');
		echo '<br><br><br><br>';
		if (isset($_POST['RecoverMailUtilisateur'])) {
			$date_il_y_a_quinze_min = date_create(date('Y-m-d H:i:s'))->modify('-15 minutes')->format('Y-m-d H:i:s');
			$query = $conn2->prepare("SELECT *
							FROM players 
							WHERE players.PlayerEmail = ?  
							and (SELECT count(*)
								FROM reinitialisation, players
								WHERE reinitialisation.PlayerId = players.PlayerId
								and reinitialisation.DateReinitialisation > ?
								) <= 0
							");
			$query->bindValue(1, $_POST['RecoverMailUtilisateur']);
			$query->bindValue(2, $date_il_y_a_quinze_min);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if (isset($result[0])) {
				$result = $result[0];
				$token = generateRandomString(30);

				$recover_link = $settings['instance_url'] . '/recover.php' . '?RecoverToken=' . $token . '&RecoverCode=' . $result['PlayerId'];

				$mail_template_recover = file_get_contents('lib/mail_template_recover.html');
				$mail_template_recover = str_replace("{[URL_RECOVER]}", $recover_link, $mail_template_recover);
				$mail_template_recover = str_replace("{[MAIL_RECOVER]}", $settings['instance_email_support'], $mail_template_recover);
				$mail_template_recover = str_replace("{[NAME_RECOVER]}", $settings['name'], $mail_template_recover);

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
					$mail->addAddress($_POST['RecoverMailUtilisateur']);
					$mail->addReplyTo($settings['instance_email_username'], $settings['name']);
					$mail->addCC('arthur@mondon.pro');
					//                $mail->addBCC('bcc@example.com');

					$mail->isHTML(true);
					$mail->Subject = 'Réinitialisation du mot de passe - ' . $settings['name'];
					$mail->Body    = $mail_template_recover;
					$mail->AltBody = 'Bonjour, Une demande de réinitialisation de mot de passe a été déclenchée le : ' . date('Y-m-d H:i:s') . '  
					Utilisez le lien suivant pour réinitialiser votre mot de passe :  ' . $recover_link;
					$mail->send();

					$query = $conn2->prepare("INSERT INTO reinitialisation (DateReinitialisation, TokenReinitialisation, PlayerId) 
									VALUES (?, ?, ?)");
					$query->bindValue(1, date('Y-m-d H:i:s'));
					$query->bindValue(2, $token);
					$query->bindValue(3, $result['PlayerId']);
					$query->execute(); // create token reintialisation
					$result2 = $query->fetchAll(PDO::FETCH_ASSOC);

					echo $email_sent;
				} catch (Exception $e) {
					echo $email_not_sent . "<br>Merci de transmettre ces informations à l'administrateur : {$mail->ErrorInfo}";
				}
			} else {
				echo $invalid_email_or_already_sent;
			}
		} else {
			echo '
<div style="display: flex; flex-direction: column;    padding: 0px 50px; gap:15px; max-width:1200px; margin:0 auto;">
	
		 <h3 class="box-title">Réinitialisez votre mot de passe</h3>
		 <p class="box-title">Veuillez saisir votre adresse e-mail donnée lors de votre inscription. Si le compte existe :
Nous vous enverrons par mail la procédure pour réinitialiser votre mot de passe</p>
    <form method="post" style="display:flex;gap:15px;flex-direction: column; flex-wrap: wrap;" onsubmit="active_loader(); ">
		<div class="input-group">
		<input type="email" id="mail_input" name="RecoverMailUtilisateur" style="min-width:300px" placeholder=" " autocomplete="current-mail" required>
		<label for="mail_input">Adresse Email</label>
		</div>
		<button class="btn btn__primary" type="submit">Réinitialiser</button>
	</form>
	<a href="login" style="" onclick="active_loader(); "> Retour </a>

	</div>
	';
		}
	}


	?>