<?php

/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 * @author     Mathis Lambert
 *
 * Contributors :
 * Kylian
 * Matthieu
 * Rayan
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
    <link rel="stylesheet" href="css/contact.css" />
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
    require('menu.php'); // afficher le menu en fonction de connecté ou pas.

    ?>

    <div class="container" style=" min-height:100vh;">
        <h2 class="head_title primary">Nous Contacter</h2>
        <section id="contact" style="margin-top:10rem;display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    align-items: center;">


            <img style=" max-width: 650px; width: 85%;" src="Elements/images/image_contact.jpg">
            <div class="inscription_form_container">

                <div id="error_container" class="error" style="display: none;"></div>

                <form action="" method="post">

                    <div class="input_container">
                        <ul class="StepProgress">
                            <li class="StepProgress-item current">
                                <div class="input-group" style="width:450px;">
                                    <input type="text" required class="box-input" style="width:100%;" name="NomUtilisateur" id="NomUtilisateur" autocomplete="new-name" placeholder=" ">
                                    <label for="NomUtilisateur">Nom</label>
                                </div>
                            </li>
                            <li class="StepProgress-item current">
                                <div class="input-group">
                                    <input type="text" required class="box-input" style="width:100%" name="PrenomUtilisateur" id="PrenomUtilisateur" autocomplete="new-surname" placeholder=" ">
                                    <label for="PrenomUtilisateur">Prénom</label>
                                </div>
                            </li>
                            <li class="StepProgress-item current">
                                <div class="input-group">
                                    <input type="email" placeholder=" " pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" required class="box-input" style="width:100%" name="MailUtilisateur" id="MailUtilisateur" autocomplete="new-mail" value="">
                                    <label for="MailUtilisateur">Adresse Email</label>
                                </div>
                            </li>
                            <li class="StepProgress-item current">
                                <div class="input-group">
                                    <input type="text" placeholder=" " required class="box-input" style="width:100%" name="Objet" id="Objet">
                                    <label for="Objet">Objet</label>
                                </div>
                            </li>
                            <li class="StepProgress-item current">
                                <div class="input-group">
                                    <input type="text" placeholder=" " required class="box-input" style="width:100%" name="Message" id="Message">
                                    <label for="Message">Message</label>
                                </div>
                            </li>
                            <center><button class="btn btn__primary" type="submit" name="send_mail">Envoyer</button></center>

                        </ul>
                    </div>
                </form>
            </div>

        </section>
    </div>


    <?php

    $email_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
<img src="Elements/icons/valid_check.gif" style="width:150px">
<h3>Le mail a bien ete envoye ! !</h3>

</div> <br><br><br>';
    $email_not_sent = ' <div style="display: flex;padding:15px;gap:15px;flex-direction: column;justify-content: center;align-items: center">
<img src="Elements/icons/invalid_cross.gif" style="width:150px">
<h3>Erreur d\'envoi</h3>
<p>Une erreur s\'est produite lors de l\'envoi du mail. </p>

</div> <br><br><br>';


    // envoi mail
    if (isset($_POST["send_mail"])) {

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
            $mail->addAddress('mmilan.tln@gmail.com');
            $mail->addReplyTo($_POST['MailUtilisateur'], $_POST['MailUtilisateur']);

            $mail->isHTML(true);
            $mail->Subject = $_POST['Objet'];
            $mail->Body    = 'Message envoyé de :' . $_POST['NomUtilisateur'] . ' ' . $_POST['PrenomUtilisateur'] . '<br>
            Contenu du message :' . $_POST['Message'];


            $mail->send();
            echo $email_sent;
        } catch (Exception $e) {
            echo $email_not_sent . "<br><p>Merci de transmettre ces informations à l'administrateur : {$mail->ErrorInfo}</p>";
        }
    }

    ?> <?php
        include './includes/footer.php';
        ?>
</body>

</html>