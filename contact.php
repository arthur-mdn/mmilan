<?php
/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 *
 * Contributors :
 * -Kylian Diochon
 * -Matthieu Cohen
 *
 */
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
<h2 class="head_title primary">Contact</h2>
    <section id="contact">

    <div class="inscription_form_container">
            
            <form method="post" onsubmit="active_loader()">
                <div id="error_container" class="error" style="display: none;"></div>
                <form action="" method="post">
                    <div class="input_container">
                        <div class="input-group">
                            <input type="text" required class="box-input" style="width:100%;" name="NomUtilisateur" id="NomUtilisateur" autocomplete="new-name" placeholder=" ">
                            <label for="NomUtilisateur">Nom</label>
                        </div>
                        <div class="input-group">
                            <input type="text" required class="box-input" style="width:100%" name="PrenomUtilisateur" id="PrenomUtilisateur" autocomplete="new-surname" placeholder=" ">
                            <label for="PrenomUtilisateur">Prénom</label>
                        </div>
                        <div class="input-group">
                            <input type="text" required class="box-input" style="width:100%" name="UsernameUtilisateur" id="UsernameUtilisateur" placeholder=" ">
                            <label for="UsernameUtilisateur">Nom d'utilisateur</label>
                        </div>
                        <div class="input-group">
                            <input type="email" placeholder=" " pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9 -]+\.[a-z]{2,}" required class="box-input" style="width:100%" name="MailUtilisateur" id="MailUtilisateur" autocomplete="new-mail" value="">
                            <label for="MailUtilisateur">Adresse Email</label>
                        </div>
                        <div class="input-group">
                            <input type="text" placeholder=" " required class="box-input" style="width:100%" name="Objet" id="Objet">
                            <label for="Objet">Objet</label>
                        </div>
                        <div class="input-group">
                            <input type="text" placeholder=" " required class="box-input" style="width:100%" name="Message" id="Message">
                            <label for="Message">Message</label>
                        </div>
                    </div>
            </form>
    </div>
    


    </section>

</body>

</html>