<!DOCTYPE html>
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Accueil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" type="image/png" href="Elements/placeholder_logo.svg" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loader.css" />
    <link rel="stylesheet" href="uikit/style/ui-kit.css"/>
    <link rel="stylesheet" href="css/media.css"/>
 
</head>

<body>
    <section id="twitch">
        <div class="el_1">
            <img src="Elements/others/TriangleJB.svg" alt="Triangle Blanc & Jaune"/>
        </div>
        <div class="el_4">
            <img src="Elements/others/Vector.svg" alt="Chemin Vectoriel parcourant la page"/>
        </div>
        <div class="twitchtitle">
            <h1 class="mediatitle"> Live Twitch </h1>
            <div class="boxshadow3"></div>
        </div>
        <div class="sectiontwitch">
            <iframe 
                class="twitchflux"
                src="https://player.twitch.tv/?channel=wolfabelle&parent=www.mmilan.fr/media.php" 
                frameborder="0" 
                allowfullscreen="true" 
                scrolling="no" 
                height="500" 
                width="889">
            </iframe> 
            <iframe
                class="twitchchat"
                id="chat_embed"
                src="https://www.twitch.tv/embed/oximuss_/chat?parent=ww.mmilan.fr/media.php"
                height="500"
                width="350">
            </iframe>   
        </div>
    </section>


    <section id="reseauxSocial">
        <div class="el_2">
            <img src="Elements/others/01blue.svg" alt="Num01 Bleu"/>
        </div>  
        <div class="rstitle">
            <h1 class="titrers">Reseaux sociaux</h1>
            <div class="boxshadow"></div>
        </div>
        <div class="informations">
            <div class="infoslan">
                <img class="logolan" src="Elements/others/Logo_blanc.png"/>
                <h1 class="landate">XX/XX/XXXX à XX:XX </h1>
            </div>
            <div class="rssociaux">
                <div class="youtubers">
                    <div class="center">
                        <img src="Elements/others/Logo_YT_jaune.png" class="logoyt"></img>
                    </div>
                    <h2 class="textcenter logoyttxt">Youtube</h2>
                </div>
                <div class="instagramrs">
                    <div class="center">
                        <img src="Elements/others/Logo_Instagram_jaune.png" class="logoinsta"></img>
                    </div>
                    <h2 class="textcenter logoinstatxt">MMi_lan2022</h2>
                </div>       
            </div>    
        </div>
    </section>


    <section id="sponsorSection">
        <div class="el_3    ">
            <img src="Elements/others/02White.svg" alt="Num02 Blanc"/>
        </div>
        <div class="sponsor">
            <div class="sponsortitle">
                <h1 class="titresponsor"> Sponsor de notre lan</h1>
                <div class="boxshadow2"></div>
            </div>

            <div class="sponsorrow1">
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
            </div>

            <div class="sponsorrow2">
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
                <div class="sponsorlogo">
                    <p class="center">Sponsor</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>