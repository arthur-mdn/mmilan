    <style>
        .nav {
            position: fixed;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0A1929;
            padding: 0 7rem;
            height: 100px;
            z-index: 10000;

            transition: all 0.3s ease-in-out;
        }

        .nav__links-container {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav__links-container a {
            text-decoration: none;
        }

        .nav__logo {
            width: 175px;
            transition: width 0.3s ease-in-out;
        }

        .button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;

            font-size: 1.25rem;
            padding: 0.8rem 2rem;

            background-color: inherit;
            border: 3px solid #fff;
            border-radius: 50px;
        }

        svg {
            fill: #fff;
            transform: rotate(180deg);
        }

        .nav__links-container__mobile {
            display: none;

        }

        .overlay-menu__mobile,
        .side-menu__mobile {
            display: none;
        }

        .nav.scrolled {
            background-color: #0A1929;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

            height: 75px;
        }

        .nav.scrolled .nav__links-container a {
            font-size: 1rem !important;
        }

        .nav.scrolled .nav__links-container .button {
            padding: 0.4rem 1rem;
        }

        .nav.scrolled .nav__logo {
            width: 150px;
        }

        @media only screen and (max-width:1300px) {
            .nav {
                padding: 0 4rem;
            }

            .nav__links-container {
                gap: 1rem;
            }
        }

        @media only screen and (max-width:1000px) {
            .nav {
                padding: 0;
                height: 85px;
            }

            .nav-links-container {
                width: 90%;
                flex-grow: 0;
            }

            .overlay-menu__mobile {
                display: block;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background-color: rgba(30, 30, 30, 0.5);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);

                z-index: 998;
                visibility: hidden;
                opacity: 0;

                transition: all 0.3s ease-in-out;

            }

            .overlay-menu__mobile.toggled {
                visibility: visible;
                opacity: 1;
            }

            .side-menu__mobile {
                position: absolute;
                top: 0;
                right: -70%;

                width: 70%;
                min-width: 275px;
                height: 100vh;
                background-color: #0A1929;

                display: flex;
                align-items: center;
                justify-content: center;

                transition: right 0.3s ease-in-out;

                z-index: 999;
            }

            .side-menu__mobile .nav__links {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }

            #side-menu.toggled {
                right: 0;
            }

            .nav__links-container {
                display: none;
            }

            .nav__links-container__mobile {
                display: flex;
            }
        }
    </style>

    <nav class="nav">
        <div class="nav-links-container row space-between" style="max-width:1900px;">

            <!-- METTRE L'URL QUI CORRESPOND POUR CHAQUE LIENS, ILS NE POINTENT VERS RIEN POUR L'INSTANT -->

            <ul class="nav__links-container">
                <li>
                    <a href="index.php" class="no-style">
                        <img class="nav__logo" src="./Elements/others/Logo_blanc.png" alt="Logo MMILan" />
                    </a>
                </li>
                <li>
                    <a href="index.php">Accueil</a>
                </li>
                <li>
                    <a href="program.php">Programme</a>
                </li>
                <li>
                    <a href="media.php">Médias</a>
                </li>
                <li>
                    <a href="teams.php">Équipes</a>
                </li>
            </ul>

            <div class="nav__links-container">
                <?php
                if (isset($_SESSION['PlayerId'])) {
                ?>
                    <a class="btn btn__light button no-style" href="login.php">
                        <?php
                        $getUsername = $conn2->prepare('SELECT * FROM players WHERE PlayerId = ?');
                        $getUsername->bindValue(1, $_SESSION['PlayerId']);
                        $getUsername->execute();
                        $UsernameResult = $getUsername->fetch(PDO::FETCH_ASSOC);

                        echo $UsernameResult['PlayerUsername'];
                        ?>
                    </a>
                <?php
                } else {
                ?>
                    <a class="btn btn__light button no-style" href="login.php">
                        <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M22.763,10.232l-4.95-4.95L16.4,6.7,20.7,11H6.617v2H20.7l-4.3,4.3,1.414,1.414,4.95-4.95a2.5,2.5,0,0,0,0-3.536Z" />
                            <path d="M10.476,21a1,1,0,0,1-1,1H3a1,1,0,0,1-1-1V3A1,1,0,0,1,3,2H9.476a1,1,0,0,1,1,1V8.333h2V3a3,3,0,0,0-3-3H3A3,3,0,0,0,0,3V21a3,3,0,0,0,3,3H9.476a3,3,0,0,0,3-3V15.667h-2Z" />
                        </svg>

                        <!-- CHANGER LE SVG, LA C'EST CELUI DE DECONNEXION, IL FAUT LE REMPLACER -->

                        Connexion
                    </a>
                <?php
                }
                ?>
            </div>

            <ul class="nav__links-container__mobile">
                <a href="index.php" class="no-style">
                    <img class="nav__logo" src="./Elements/others/Logo_blanc.png" alt="Logo MMILan" />
                </a>
            </ul>

            <div class="nav__links-container__mobile burger-menu">
                <span></span><span></span><span></span>
            </div>

        </div>
        <div class="overlay-menu__mobile" id="overlay-menu">
        </div>
        <div class="side-menu__mobile" id="side-menu">
            <div class="nav__links">

                <a href="index.php">Accueil</a>

                <a href="program.hp">Programme</a>

                <a href="media.php">Médias</a>

                <a href="teams.php">Équipes</a>

            </div>
        </div>
    </nav>

    <script>
        const button = document.querySelector('.button');
        const svg = document.querySelector('svg');

        //add an event listener for the button hover
        /*    if (button) {
               svg.style.transition = 'all 0.3 ease';
               button.addEventListener('mouseover', () => {
                   svg.style.fill = '#0A1929';
               });
               button.addEventListener('mouseout', () => {
                   svg.style.fill = '#fff';
               });
           } */
    </script>