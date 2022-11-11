// set current width and height of the screen to --screen-width and --screen-height
const rootcss = document.documentElement.style;
rootcss.setProperty("--screen-width", window.innerWidth + "px");
rootcss.setProperty("--screen-height", window.innerHeight + "px");

window.addEventListener("resize", () => {
  rootcss.setProperty("--screen-width", window.innerWidth + "px");
  rootcss.setProperty("--screen-height", window.innerHeight + "px");
});

function close_modal(id) {
  document.getElementById("modal_" + id).outerHTML = "";
}

function hideIt(elId, timer = 5000) {
  setTimeout(() => {
    const elementToHide = document.getElementById(elId);
    if (elementToHide !== null) {
      elementToHide.outerHTML = "";
    } else {
      console.log("Already deleted");
    }
  }, timer);
}

window.onload = function () {
  rootcss.setProperty("--body-width", document.body.clientWidth + "px");
  rootcss.setProperty("--body-height", document.body.clientHeight + "px");

  window.addEventListener("resize", () => {
    rootcss.setProperty("--body-width", document.body.clientWidth + "px");
    rootcss.setProperty("--body-height", document.body.clientHeight + "px");
  });

  let pageParams = new URLSearchParams(window.location.search);
  let errorMsg = pageParams.get("error");
  let hack_attempt = pageParams.get("hack_log");
  const gameInput = document.getElementById("FavGameUtilisateur");

  if (gameInput) {
    if (hack_attempt == "true") {
      gameInput.classList.add("error");
      gameInput.addEventListener("focus", () => {
        gameInput.classList.remove("error");
      });
    } else {
      gameInput.classList.remove("error");
    }
  }

  if (errorMsg) {
    let error = document.getElementById("error_container");
    error.style.display = "block";
    error.innerHTML = errorMsg.split("-").join(" ");
  }

  const burgers = document.querySelectorAll(".burger-menu");
  const sideMenu = document.getElementById("side-menu");
  const overlayMenu = document.getElementById("overlay-menu");
  if (burgers && sideMenu && overlayMenu) {
    burgers.forEach((burger) => {
      burger.addEventListener("click", () => {
        burger.classList.toggle("active");
        sideMenu.classList.toggle("toggled");
        overlayMenu.classList.toggle("toggled");
        overlayMenu.addEventListener("click", () => {
          burger.classList.remove("active");
          sideMenu.classList.remove("toggled");
          overlayMenu.classList.remove("toggled");
        });
      });
    });
  }

  const navbar = document.querySelector(".nav");
  if (navbar) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
  }

  const cursor = document.querySelector(".cursor");
  const links_viewer = document.querySelectorAll(".section-viewer ul li a");

  if (links_viewer && cursor) {
    const sectionWidth = links_viewer[0].getBoundingClientRect().height;
    // check where the user is on the page
    window.addEventListener("scroll", () => {
      const scroll = window.scrollY;
      const sections = document.querySelectorAll("section");

      sections.forEach((section, index) => {
        if (scroll > section.offsetTop - window.innerHeight / 2) {
          links_viewer.forEach((link) => {
            link.classList.remove("active");
          });
          links_viewer[index].classList.add("active");
          cursor.style.top = index * sectionWidth + "px";
        }
      });
    });
  }
};
