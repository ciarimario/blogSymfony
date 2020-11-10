const commentForm = document.getElementById("comment-form");

commentForm.addEventListener("submit", ajaxSubmit);

function ajaxSubmit(e) {
  // on annule le comportement par défaut du submit du formulaire
  e.preventDefault();

  // on récupère l'url de la page
  /* let url = window.location.href; */
  const url = new URL(window.location.href);

  // on récup-re les données du formulaire
  let formData = new FormData(commentForm);

  // ajax

  const options = {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  };

  fetch(url + "/add-comment", options)
    .then((response) => response.text())
    .then((html) => {
      const container = document.querySelector(".comments-list");
      console.log(container);
      container.innerHTML = html + container.innerHTML;

      $("#add-comment-success-modal").modal("show");
      commentForm.reset();
    });
}
