# TODO
1. renommer les fichiers en php
2. branch + commit
3. include header et footer : Créer un dossier includes/ ou partials/ pour tous les éléments réutilisables comme header/footer. Utiliser require_once : évite les doublons et les erreurs.
Utiliser des chemins relatifs clairs pour ne pas dépendre de l’environnement serveur. Nommer les fichiers de manière explicite.
4. merise
5. recréer la base de données
6. attaquer refactorer php + pdo + mysql

## SEO

- [] index.html qui est la première page du site
- [] `<title>`Unique et pertinent pour chaque page (50 à 60 caractères)

- [] Meta description sur chaque page (150 à 160 caractères) dans `<head>`:
    `<meta name="description" content="...">` 
- [] Présence de balises sémantiques HTML5 (`<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<asise>`, `<footer>`, etc.)
- [] Vérifier l'ordre des balises sémantiques (h1 bien en haut de la page par exemple)
- [] Utilisation appropriée des balises de titres (`<h1>`, `<h2>`, `<h3>`, etc.) pour structurer le contenu
- [] Images :
    - [] Texte alternatif (`alt=`) descriptif pour toutes les images
    - [] Compression des images pour un chargement rapide
- [] Liens :
    - [] Liens internes pertinents entre les pages du site
    - [] Liens externes vers des sites de haute autorité
- [] Accessibilité :
    - [] **Contraste** suffisant entre le texte et l'arrière-plan
    - [] **Navigation** au clavier possible
    - [] Utilisation d'**aria roles** et labels si nécessaire
- [] Faire des tests avec lighthouse dans Chrome DevTools pour vérifier les performances SEO
- [] robots.txt
- [] sitemap.xml