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
  let pageParams = new URLSearchParams(window.location.search);
  let errorMsg = pageParams.get("error");
  let hack_attempt = pageParams.get("hack_log");
  const gameInput = document.getElementById("FavGameUtilisateur");
  const gameHackMessage = document.getElementById("game-error-msg");

  if (gameInput) {
    if (hack_attempt == "true") {
      gameInput.classList.add("error");
      gameHackMessage.style.display = "block";
      gameInput.addEventListener("focus", () => {
        gameInput.classList.remove("error");
      });
    } else {
      gameInput.classList.remove("error");
      gameHackMessage.style.display = "none";
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
};
