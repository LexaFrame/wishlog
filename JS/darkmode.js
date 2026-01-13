// Détection de la préférence de thème du système :
const darkModeRequest = window.matchMedia("(prefers-color-scheme: dark)");

// Cas 1 - Si préférence thème sombre, application au chargement,par l'activation du CSS correspondant :
if (darkModeRequest.matches) {
    document.documentElement.classList.add("dark");
}

// Cas 2 - Si le mode sombre est activé pendant la navigation sur le site, on bascule sur le mode sombre
darkModeRequest.addEventListener("change", (event) => {
    if (event.matches) {
        document.documentElement.classList.add("dark");
    } else { // Sinon, on retourne au mode clair !
        document.documentElement.classList.remove("dark");
    }
});