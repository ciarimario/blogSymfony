//////////////////////// code principal /////////////////////

// on cible bouton publier
const btnPubly = document.querySelectorAll(".btnPubly");

// on cible bouton effacer
const btnCancel = document.querySelectorAll(".btnCancel");

btnPubly.forEach((btn) => btn.addEventListener("click", publy));
btnCancel.forEach((btn) => btn.addEventListener("click", cancel));

//////////////////////// fonctions ///////////////////////////////:
function publy(event) {
  event.preventDefault();
  const commentId = event.currentTarget.getAttribute("data-comment-id");

  fetch(`/admin/comments/${commentId}/publy`)
    .then((response) => response.text())
    .then((id) => {
      document.getElementById(`status-${id}`).innerHTML = "En ligne";
      document.getElementById(`status-${id}`).classList.remove("text-danger");
      document.getElementById(`status-${id}`).style.color = "green";
    });
}
function cancel(event) {
  event.preventDefault();

  const commentId = event.currentTarget.getAttribute("data-comment-id");
  const divComment = document.getElementById(commentId);

  // requete ajax
  fetch(`/admin/comment/${commentId}/remove`)
    .then((response) => response.text())
    .then((id) => {
      alert(id);
      document.getElementById(id).remove();
    });

  // a mettre dans le then du fetch
}
