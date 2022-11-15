<footer>
    <style>
        footer {
            background-color: #0a1929;
            padding: 2rem 0;
        }

        .flex-column {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .flex-row {
            margin: 1.5rem 0;
            display: flex;
            gap: 3rem;
        }

        .yellow-flex-row {
            margin: 1.5rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5rem;
        }

        .footer-link {
            color: var(--color-light);
        }

        .footer-link,
        .footer-container a {
            text-decoration: none !important;
        }

        @media screen and (max-width: 600px) {
            .flex-row {
                font-size: 0.9rem;
                gap: .5rem;
            }

            .yellow-flex-row {
                font-size: 0.6rem;
                gap: .5rem;
            }

            .connexion-btn {
                font-size: 1rem;
                padding: 0.4rem 0.9rem
            }
        }
    </style>

    <div class="footer-container flex-column">
        <a href="./index.php"><img src="./Elements/others/Logo_blanc.png" alt="logo mmilan" width="200px"></a>
        <div class="flex-row">
            <a class="footer-link" href="./index.php">ACCUEIL</a>
            <!--             &VerticalLine;
            <a class="footer-link" href="./program.php">PROGRAMME</a>
            &VerticalLine;
            <a class="footer-link" href="./media.php">MEDIAS</a> -->
            &VerticalLine;
            <a class="footer-link" href="./teams.php">EQUIPES</a>
        </div>
        <?php
        if (isset($_SESSION['PlayerId'])) {
        ?>
            <a class="btn btn__light button no-style" href="profile">
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
            <a class="btn btn__light button no-style connexion-btn" href="login">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                    <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                </svg>
                Connexion
            </a>
        <?php
        }
        ?>
        <div class="yellow-flex-row" style="width: 100%; height: 5rem; background-color: #f9d71c;">
            <h3 style="font-weight: 700;"><strong style="color: #0a1929;">Une question ?</strong></h3>
            <h3><a class="btn btn__dark" style="text-decoration: none; font-weight: 600;" href="mailto:mmilan.tln@gmail.com">Nous contacter</a></h3>
        </div>
        <div class="flex-row" style="margin-top: 1rem;">
            <a href="https://youtube.com/" target="_blank" class="no-style">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="40px" height="40px">
                    <path fill="#f9d71c" d="M 44.898438 14.5 C 44.5 12.300781 42.601563 10.699219 40.398438 10.199219 C 37.101563 9.5 31 9 24.398438 9 C 17.800781 9 11.601563 9.5 8.300781 10.199219 C 6.101563 10.699219 4.199219 12.199219 3.800781 14.5 C 3.398438 17 3 20.5 3 25 C 3 29.5 3.398438 33 3.898438 35.5 C 4.300781 37.699219 6.199219 39.300781 8.398438 39.800781 C 11.898438 40.5 17.898438 41 24.5 41 C 31.101563 41 37.101563 40.5 40.601563 39.800781 C 42.800781 39.300781 44.699219 37.800781 45.101563 35.5 C 45.5 33 46 29.398438 46.101563 25 C 45.898438 20.5 45.398438 17 44.898438 14.5 Z M 19 32 L 19 18 L 31.199219 25 Z" />
                </svg>
            </a>
            <a href="https://instagram.com/mmi_lan2022" target="_blank" class="no-style">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="40px" height="40px">
                    <path fill="#f9d71c" d="M 21.580078 7 C 13.541078 7 7 13.544938 7 21.585938 L 7 42.417969 C 7 50.457969 13.544938 57 21.585938 57 L 42.417969 57 C 50.457969 57 57 50.455062 57 42.414062 L 57 21.580078 C 57 13.541078 50.455062 7 42.414062 7 L 21.580078 7 z M 47 15 C 48.104 15 49 15.896 49 17 C 49 18.104 48.104 19 47 19 C 45.896 19 45 18.104 45 17 C 45 15.896 45.896 15 47 15 z M 32 19 C 39.17 19 45 24.83 45 32 C 45 39.17 39.169 45 32 45 C 24.83 45 19 39.169 19 32 C 19 24.831 24.83 19 32 19 z M 32 23 C 27.029 23 23 27.029 23 32 C 23 36.971 27.029 41 32 41 C 36.971 41 41 36.971 41 32 C 41 27.029 36.971 23 32 23 z" />
                </svg>
            </a>
        </div>
        <p style="text-align: center; margin: 1rem;">&copy;Copyright 2022 - IUT MMI TOULON - développé avec ❤️ avec la participation de tout le pôle DEV</p>
    </div>
</footer>