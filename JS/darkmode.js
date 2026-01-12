const darkModeRequest = window.matchMedia("(prefers-color-scheme: dark)");
if (darkModeRequest.matches) {
    document.documentElement.classList.add("dark");
}

darkModeRequest.addEventListener("change", (event) => {
    if (event.matches) {
        document.documentElement.classList.add("dark");
    } else { 
        document.documentElement.classList.remove("dark");
    }
});