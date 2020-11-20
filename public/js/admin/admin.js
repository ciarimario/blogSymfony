////////////////////////////////////////////////////////////////
///////////////////// FONCTIONS ///////////////////////////////
//////////////////////////////////////////////////////////////

// Supprime le post en base de données, puis supprime physiquement de l'affichage sur la page
function onClickPostRemove(event) {
  event.preventDefault();

  const id = event.currentTarget.dataset.id;

  confirmRemoveButton.dataset.id = id;

  $("#remove-post-confirm-modal").modal("show");
}

// Valide ou non la suppression du post
function onClickConfirmRemovePost() {
  $("#remove-post-confirm-modal").modal("hide");

  const id = event.currentTarget.dataset.id;
  const url = `/admin/post/remove/${id}`;
  const options = {
    method: "POST",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  };

  fetch(url, options)
    .then((response) => response.json())
    .then((id) => {
      document.getElementById(`post-${id}`).remove();
      $("#remove-post-success-modal").modal("show");
    });
}

/////////////////////////////////////////////////////////////
///////////////// code principal //////////////////////////
///////////////////////////////////////////////////////////

// on cible le bouton confirmer dans la modal de confirmation
const confirmRemoveButton = document.querySelector(
  "#remove-post-confirm-modal .confirm-button"
);

// on met un écouteur sur ce bouton
confirmRemoveButton.addEventListener("click", onClickConfirmRemovePost);

// on cible le bouton suppression post
const btnRemove = document.querySelectorAll(".post-remove");

// Au click sur le bouton supprimer (picto pubelle)
btnRemove.forEach((btn) => {
  btn.addEventListener("click", onClickPostRemove);
});
