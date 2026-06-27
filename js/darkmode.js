/**
 * @author Sarah Segui Bilger
 * @project WishLog
 * @context Educational project – public repository required by training
 */


// Détection de la préférence de thème du système :
const darkModeRequest = window.matchMedia("(prefers-color-scheme: dark)");

// Cas 1 - Si préférence thème sombre, application au chargement par l'activation du CSS correspondant :
if (darkModeRequest.matches) {
    document.documentElement.classList.add("dark");
}

// Cas 2 - Si la préférence système change pendant la navigation, on bascule en conséquence (sombre ou clair) :
darkModeRequest.addEventListener("change", (event) => {
    if (event.matches) {
        document.documentElement.classList.add("dark"); // Si l'event correspond à sombre, on bascule à sombre
    } else { // Sinon, on retourne au mode clair !
        document.documentElement.classList.remove("dark");
    }
});