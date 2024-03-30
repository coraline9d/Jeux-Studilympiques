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
        const reservationRow = button.parentElement.parentElement;
        const totalCostElement = document.querySelector("#total-cost");

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
              reservationRow.remove(); // Supprimez la ligne de réservation de la table
              // Mettez à jour le prix total affiché sur la page avec le nouveau prix total reçu de la réponse
              totalCostElement.textContent = data.totalCost;
              // Appel de la méthode totalCost pour recalculer le prix total après la suppression
              fetch("/reservation/total-cost")
                .then((response) => response.json())
                .then((data) => {
                  // Accédez à la propriété totalCost de l'objet JSON
                  let totalCost = data.totalCost;
                  // Mettez à jour le coût total affiché sur la page avec le nouveau prix total
                  totalCostElement.textContent = totalCost;
                })
                .catch((error) => {
                  console.error(
                    "Une erreur s'est produite lors du calcul du prix total :",
                    error
                  );
                  // Affichez une alerte ou un message d'erreur en cas d'échec du calcul du prix total
                });
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
