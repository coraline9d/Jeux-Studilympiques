// document.addEventListener("DOMContentLoaded", () => {
//   // Sélectionnez tous les boutons de suppression de réservation
//   const deleteButtons = document.querySelectorAll(".delete-button");

//   // Ajoutez un écouteur d'événements à chaque bouton de suppression de réservation
//   deleteButtons.forEach((button) => {
//     button.addEventListener("click", (event) => {
//       event.preventDefault(); // Empêcher le comportement par défaut du lien de suppression

//       // Récupérez l'URL de suppression à partir de l'attribut "href" du bouton de suppression
//       const deleteUrl = button.getAttribute("href");

//       // Récupérez l'identifiant de la réservation à partir de l'attribut "data-reservation-id" du bouton de suppression
//       const reservationId = button.dataset.reservationId;

//       // Envoyez une requête AJAX pour supprimer la réservation
//       fetch(deleteUrl, {
//         method: "POST",
//         headers: {
//           "X-Requested-With": "XMLHttpRequest", // Indiquez une requête AJAX
//           "Content-Type": "application/json", // Indiquez le type de contenu de la requête
//           "X-CSRF-Token": document
//             .querySelector('meta[name="csrf-token"]')
//             .getAttribute("content"), // Ajoutez le token CSRF
//         },
//         body: JSON.stringify({ reservationId: reservationId }), // Envoyez l'identifiant de la réservation dans le corps de la requête
//       })
//         .then((response) => {
//           if (!response.ok) {
//             throw new Error("La suppression de la réservation a échoué.");
//           }
//           // Retournez une promesse résolue avec une chaîne vide pour indiquer le succès
//           return response.text();
//         })
//         .then(() => {
//           // Rechargez la page pour refléter les modifications
//           window.location.reload();
//         })
//         .catch((error) => {
//           console.error(
//             "Une erreur s'est produite lors de la suppression de la réservation :",
//             error
//           );
//           // Affichez une alerte ou un message d'erreur en cas d'échec de suppression de la réservation
//         });
//     });
//   });
// });

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".delete-button").forEach(function (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      if (confirm("Êtes-vous sûr de vouloir supprimer cette réservation ?")) {
        fetch(button.getAttribute("href"), {
          method: "POST",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ _token: button.dataset.token }),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then((data) => {
            if (data.status === "success") {
              button.parentElement.parentElement.remove();
            } else {
              alert("Une erreur s'est produite");
            }
          })
          .catch((error) => {
            console.log("Fetch Error :-S", error);
            alert("Une erreur s'est produite");
          });
      }
    });
  });
});
