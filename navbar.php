    <style>
        .nav {
            display: flex;
            align-items: center;
            background-color: #0A1929;
            padding: 0 7rem;
            height: 100px;
        }

        .nav__links-container {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav__logo {
            width: 200px;
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
    </style>

    <nav class="nav">
        <div class="nav-links-container row space-between">

            <!-- METTRE L'URL QUI CORRESPOND POUR CHAQUE LIENS, ILS NE POINTENT VERS RIEN POUR L'INSTANT -->

            <ul class="nav__links-container">
                <li>
                    <a href="index.php" class="no-style">
                        <img class="nav__logo" src="./Elements/others/Logo_blanc.png" alt="Logo MMILan" />
                    </a>
                </li>
                <li>
                    <a href="#">Accueil</a>
                </li>
                <li>
                    <a href="#">Actualités</a>
                </li>
                <li>
                    <a href="#">Médias</a>
                </li>
                <li>
                    <a href="#">Équipes</a>
                </li>
            </ul>

            <div class="nav__links-container">
                <button class="btn btn__light button">
                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="24" height="24">
                        <path d="M22.763,10.232l-4.95-4.95L16.4,6.7,20.7,11H6.617v2H20.7l-4.3,4.3,1.414,1.414,4.95-4.95a2.5,2.5,0,0,0,0-3.536Z" />
                        <path d="M10.476,21a1,1,0,0,1-1,1H3a1,1,0,0,1-1-1V3A1,1,0,0,1,3,2H9.476a1,1,0,0,1,1,1V8.333h2V3a3,3,0,0,0-3-3H3A3,3,0,0,0,0,3V21a3,3,0,0,0,3,3H9.476a3,3,0,0,0,3-3V15.667h-2Z" />
                    </svg>

                    <!-- CHANGER LE SVG, LA C'EST CELUI DE DECONNEXION, IL FAUT LE REMPLACER -->

                    Connexion
                </button>
            </div>

        </div>
    </nav>

    <script>
        const button = document.querySelector('.button');
        const svg = document.querySelector('svg');

        //add an event listener for the button hover
        if (button) {
            svg.style.transition = 'all 0.3 ease';
            button.addEventListener('mouseover', () => {
                svg.style.fill = '#0A1929';
            });
            button.addEventListener('mouseout', () => {
                svg.style.fill = '#fff';
            });
        }
    </script>