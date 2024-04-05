// Check if the user has already made a choice
if (!document.cookie.includes("accept_cookies")) {
  // Afficher la bannière de cookies
  document.getElementById("cookie-banner").style.display = "block";
}

// Manage the click on the "Accept" button
document.getElementById("accept-cookies").addEventListener("click", () => {
  // User has accepted cookies
  document.cookie =
    "accept_cookies=true; expires=" +
    new Date(Date.now() + 86400000).toUTCString() +
    "; path=/";

  // Hide cookie banner
  document.getElementById("cookie-banner").style.display = "none";
});

// Manage the click on the "Refuse" button
document.getElementById("refuse-cookies").addEventListener("click", () => {
  // User has refused cookies
  document.cookie =
    "accept_cookies=false; expires=" +
    new Date(Date.now() + 86400000).toUTCString() +
    "; path=/";

  // Hide cookie banner
  document.getElementById("cookie-banner").style.display = "none";
});
