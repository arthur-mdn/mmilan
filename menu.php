<style>
    .nav {
        position: fixed;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #0A1929;
        padding: 0 20px;
        top: 0;
        height: 95px;
        z-index: 10000;

        transition: all 0.3s ease-in-out;
    }

    .nav-links-container {
        padding: 0 20px;
        max-width: 1300px;

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
        padding: 0.6rem 1.25rem;

        background-color: inherit;
        border: 3px solid #fff;
        border-radius: 50px;
    }

    .button:hover svg {
        fill: var(--color-dark);
    }

    svg {
        fill: #F7F7F7;
        transition: all 0.3s ease-in-out;
    }

    .nav__links-container__mobile {
        display: none;

    }

    .overlay-menu__mobile,
    .side-menu__mobile {
        display: none;
    }

    .side-menu__mobile .button {
        font-size: 1rem;
        padding: 0.4rem 0.9rem;
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

        .nav-links-container {
            padding: 0;

        }

        .nav__links-container {
            gap: 1rem;
        }
    }

    @media only screen and (max-width:1000px) {
        .nav {
            height: 85px;
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

        .side-menu__mobile.toggled {
            min-width: 240px;
        }

        .side-menu__mobile {
            position: absolute;
            top: 0;
            right: -70%;

            width: 70%;

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
            gap: 1rem;
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

        .nav__links a {
            text-decoration: none;
        }
    }
</style>

<nav class="nav">
    <div class="nav-links-container row space-between"">

    <ul class=" nav__links-container">
        <li>
            <a href="index" class="no-style">
                <img class="nav__logo" src="./Elements/others/Logo_blanc.png" alt="Logo MMILan" />
            </a>
        </li>
        <li>
            <a href="program">Programme</a>
        </li>
        <li>
            <a href="media">Médias</a>
        </li>
        <li>
            <a href="teams">Équipes</a>
        </li>
        </ul>

        <div class="nav__links-container">
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
                <a class="btn btn__light button no-style" href="login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                        <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                    </svg>
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


            <a href="program">Programme</a>

            <a href="media">Médias</a>

            <a href="teams">Équipes</a>

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
                <a class="btn btn__light button no-style" href="login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                        <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                    </svg>
                    Connexion
                </a>
            <?php
            }
            ?>

        </div>
    </div>
</nav>