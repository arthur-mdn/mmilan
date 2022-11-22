<meta charset="utf-8">

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    footer {
        background-color: #0a1929;
        color: #fff;
        padding: 2rem;
    }
    .footer-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .footer-nav {
        display: flex;
        gap: 1rem;
        list-style: none;
    }
    .footer-link {
        text-transform: uppercase;
        text-decoration: none;
        color: #fff;
    }
    .sponsors-container {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin: 2rem 0;
    }
    .sponsor {
        width: 120px;
        height: 120px;
        background-color: #f9d71c;
    }

    @media screen and (max-width: 980px) {
        .footer-container {
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .socials-container {
            margin: 1rem;
        }
        .sponsors-container {
            margin: inherit;
        }
    }
</style>

<footer>
    <div class="footer-container">
        <img src="./Elements/others/Logo_blanc.png" alt="logo MMILAN" width="200px">
        <ul class="footer-nav">
            <li class="footer-item"><a class="footer-link" href="">programme</a></li>
                &VerticalLine;
            <li class="footer-item"><a class="footer-link" href="">équipes</a></li>
                &VerticalLine;
            <li class="footer-item"><a class="footer-link" href="">live</a></li>
                &VerticalLine;
            <li class="footer-item"><a class="footer-link" href="">sponsors</a></li>
                &VerticalLine;
            <li class="footer-item"><a class="footer-link" href="">contact</a></li>
        </ul>
        <div class="socials-container">
            <a href="https://instagram.com/mmi_lan2022" target="_blank" class="no-style">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="40px" height="40px">
                    <path fill="#f9d71c" d="M 21.580078 7 C 13.541078 7 7 13.544938 7 21.585938 L 7 42.417969 C 7 50.457969 13.544938 57 21.585938 57 L 42.417969 57 C 50.457969 57 57 50.455062 57 42.414062 L 57 21.580078 C 57 13.541078 50.455062 7 42.414062 7 L 21.580078 7 z M 47 15 C 48.104 15 49 15.896 49 17 C 49 18.104 48.104 19 47 19 C 45.896 19 45 18.104 45 17 C 45 15.896 45.896 15 47 15 z M 32 19 C 39.17 19 45 24.83 45 32 C 45 39.17 39.169 45 32 45 C 24.83 45 19 39.169 19 32 C 19 24.831 24.83 19 32 19 z M 32 23 C 27.029 23 23 27.029 23 32 C 23 36.971 27.029 41 32 41 C 36.971 41 41 36.971 41 32 C 41 27.029 36.971 23 32 23 z" />
                </svg>
            </a>
            <a href="" target="_blank" class="no-style">
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="40px" height="40px">
                    <path fill="#f9d71c" d="M 41.625 10.769531 C 37.644531 7.566406 31.347656 7.023438 31.078125 7.003906 C 30.660156 6.96875 30.261719 7.203125 30.089844 7.589844 C 30.074219 7.613281 29.9375 7.929688 29.785156 8.421875 C 32.417969 8.867188 35.652344 9.761719 38.578125 11.578125 C 39.046875 11.867188 39.191406 12.484375 38.902344 12.953125 C 38.710938 13.261719 38.386719 13.429688 38.050781 13.429688 C 37.871094 13.429688 37.6875 13.378906 37.523438 13.277344 C 32.492188 10.15625 26.210938 10 25 10 C 23.789063 10 17.503906 10.15625 12.476563 13.277344 C 12.007813 13.570313 11.390625 13.425781 11.101563 12.957031 C 10.808594 12.484375 10.953125 11.871094 11.421875 11.578125 C 14.347656 9.765625 17.582031 8.867188 20.214844 8.425781 C 20.0625 7.929688 19.925781 7.617188 19.914063 7.589844 C 19.738281 7.203125 19.34375 6.960938 18.921875 7.003906 C 18.652344 7.023438 12.355469 7.566406 8.320313 10.8125 C 6.214844 12.761719 2 24.152344 2 34 C 2 34.175781 2.046875 34.34375 2.132813 34.496094 C 5.039063 39.605469 12.972656 40.941406 14.78125 41 C 14.789063 41 14.800781 41 14.8125 41 C 15.132813 41 15.433594 40.847656 15.621094 40.589844 L 17.449219 38.074219 C 12.515625 36.800781 9.996094 34.636719 9.851563 34.507813 C 9.4375 34.144531 9.398438 33.511719 9.765625 33.097656 C 10.128906 32.683594 10.761719 32.644531 11.175781 33.007813 C 11.234375 33.0625 15.875 37 25 37 C 34.140625 37 38.78125 33.046875 38.828125 33.007813 C 39.242188 32.648438 39.871094 32.683594 40.238281 33.101563 C 40.601563 33.515625 40.5625 34.144531 40.148438 34.507813 C 40.003906 34.636719 37.484375 36.800781 32.550781 38.074219 L 34.378906 40.589844 C 34.566406 40.847656 34.867188 41 35.1875 41 C 35.199219 41 35.210938 41 35.21875 41 C 37.027344 40.941406 44.960938 39.605469 47.867188 34.496094 C 47.953125 34.34375 48 34.175781 48 34 C 48 24.152344 43.785156 12.761719 41.625 10.769531 Z M 18.5 30 C 16.566406 30 15 28.210938 15 26 C 15 23.789063 16.566406 22 18.5 22 C 20.433594 22 22 23.789063 22 26 C 22 28.210938 20.433594 30 18.5 30 Z M 31.5 30 C 29.566406 30 28 28.210938 28 26 C 28 23.789063 29.566406 22 31.5 22 C 33.433594 22 35 23.789063 35 26 C 35 28.210938 33.433594 30 31.5 30 Z"/>
                </svg>
            </a>
            <a href="https://youtube.com/" target="_blank" class="no-style">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="40px" height="40px">
                    <path fill="#f9d71c" d="M 44.898438 14.5 C 44.5 12.300781 42.601563 10.699219 40.398438 10.199219 C 37.101563 9.5 31 9 24.398438 9 C 17.800781 9 11.601563 9.5 8.300781 10.199219 C 6.101563 10.699219 4.199219 12.199219 3.800781 14.5 C 3.398438 17 3 20.5 3 25 C 3 29.5 3.398438 33 3.898438 35.5 C 4.300781 37.699219 6.199219 39.300781 8.398438 39.800781 C 11.898438 40.5 17.898438 41 24.5 41 C 31.101563 41 37.101563 40.5 40.601563 39.800781 C 42.800781 39.300781 44.699219 37.800781 45.101563 35.5 C 45.5 33 46 29.398438 46.101563 25 C 45.898438 20.5 45.398438 17 44.898438 14.5 Z M 19 32 L 19 18 L 31.199219 25 Z" />
                </svg>
            </a>
        </div>
    </div>
    <div class="footer-container2">
        <div class="sponsors-container">
            <div class="sponsor"></div>
            <div class="sponsor"></div>
            <div class="sponsor"></div>
            <div class="sponsor"></div>
        </div>
    </div>
    <p style="text-align: center; margin: 3rem auto 1rem auto; max-width: 450px;">&copy;Copyright 2022 - IUT MMI TOULON - développé avec ❤️ avec la participation de tout le pôle DEV</p>
</footer>