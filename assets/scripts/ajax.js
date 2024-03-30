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
