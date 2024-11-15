// Fonction pour ouvrir le pop-up
function openPopup() {
    document.getElementById("popup-overlay").classList.add("active");
}

// Fonction pour fermer le pop-up
function closePopup() {
    document.getElementById("popup-overlay").classList.remove("active");
}

// Ferme la pop-up si on clique en dehors de celle-ci
document.getElementById("popup-overlay").addEventListener("click", function () {
    closePopup();
});