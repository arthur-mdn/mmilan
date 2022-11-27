<style>
    .nav {
        position: fixed;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;

        padding: 0 20px;
        background-color: transparent;

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

        font-size: 1rem;

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
            <a href="programme">Programme</a>
        </li>
        <li>
            <a href="teams">Équipes</a>
        </li>
        <li>
            <a href="live">Live</a>
        </li>
        <li>
            <a href="sponsors">Sponsors</a>
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


            <a href="/">Accueil</a>

            <a href="programme">Programme</a>
            <a href="teams">Équipes</a>
            <a href="live">Live</a>
            <a href="sponsors">Sponsors</a>

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
                    Connexion
                </a>
            <?php
            }
            ?>

        </div>
    </div>
</nav>