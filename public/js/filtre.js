const modal = document.getElementById("filterModal");
const btn = document.getElementById("openFilter");
const span = document.getElementsByClassName("close")[0];

// Ouvrir la modale
btn.onclick = function() {
    modal.style.display = "block";
}

// Fermer avec la croix
span.onclick = function() {
    modal.style.display = "none";
}

// Fermer si on clique n'importe où en dehors de la boîte blanche
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}