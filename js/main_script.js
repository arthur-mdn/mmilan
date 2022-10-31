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
