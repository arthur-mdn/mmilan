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

let pageParams = new URLSearchParams(window.location.search);
let hack_attempt = pageParams.get("hack_log");
const gameInput = document.getElementById("FavGameUtilisateur");
const gameHackMessage = document.getElementById("game-error-msg");

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
