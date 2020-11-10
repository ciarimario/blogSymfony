function removeFlashMessage() {
  // on cible l'element qui contient le message flash
  const flashMessage = document.getElementById("flashMessage");

  // on fait disparaitre de l'affichage le message flash
  setTimeout(() => {
    flashMessage.animate(
      [
        // keyframes
        { opacity: 1 },
        { opacity: 0 },
      ],
      {
        // timing options
        duration: 1000,
        fill: "forwards",
      }
    );
  }, 5000);
  // on retire du Dom le message flash
  setTimeout(() => {
    flashMessage.style.display = "none";
  }, 6000);
}

removeFlashMessage();
