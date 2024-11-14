const minimumAnimationDuration = 5000; // Durée minimale de l'animation en millisecondes
let animationActive = true; // Indicateur pour garder l'animation active
let animationStartTime = Date.now(); // Heure de début de l'animation

function startAnimation() {
  const container = document.getElementById("animationOverlay");
  container.style.display = "block";  // Affiche l'overlay

  // Démarre l'animation avant la redirection
  playAnimation();

  // Redirection pour recharger la page après un court délai
  setTimeout(() => {
    window.location.href = window.location.href;
  }, 50);  // Petit délai pour que l'animation démarre avant la redirection
}

function playAnimation() {
  const container = document.getElementById("animationOverlay");
  const symbols = ["★", "✨", "⚙️", "🤖", "💫", "✦", "⚡", "💩"]; // Étoiles, symboles d'IA et icône caca

  const interval = 20; // Intervalle pour générer de nouveaux symboles (en millisecondes)

  // Fonction pour générer un symbole à une position aléatoire
  function generateSymbol() {
    if (!animationActive) return; // Stoppe l'animation si elle n'est plus active

    const symbolElement = document.createElement("div");
    symbolElement.className = "symbol";
    symbolElement.textContent = symbols[Math.floor(Math.random() * symbols.length)];

    // Position aléatoire pour chaque symbole
    symbolElement.style.left = Math.random() * 100 + "vw";
    symbolElement.style.top = Math.random() * 100 + "vh";

    container.appendChild(symbolElement);

    // Supprimer le symbole après la fin de son animation (1 seconde)
    setTimeout(() => {
      symbolElement.remove();
    }, 1000); // Durée d'apparition du symbole

    // Planifier l'apparition du prochain symbole
    setTimeout(generateSymbol, interval);
  }

  // Démarrer la génération de symboles
  generateSymbol();
}

// Arrêter l'animation lorsque le chargement de la page est terminé
window.onload = () => {
  document.getElementById("animationOverlay").style.display = "none";
  animationActive = true;
};

// Continuer l'animation tant que la page n'est pas complètement chargée
document.onreadystatechange = () => {
  if (document.readyState === "complete") {
    document.getElementById("animationOverlay").style.display = "none";
    animationActive = false;
  }
};