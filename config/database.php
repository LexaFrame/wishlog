<?php
// database.php centralise la connexion à la base de données MySQL via PDO et est inclus dans toutes les pages grâce à require_once 'config/database.php'.
// Les identifiants de connexion sont récupérés depuis les variables d'environnement Docker pour ne jamais exposer de données sensibles dans le code versionné sur Git.


// Début de la gestion des erreurs avec try/catch : avec try on tente la connexion, si elle échoue on bascule sur le catch : 
try {
    // Création de l'objet PDO à partir de la classe PDO. La variable $pdo créée ici sert à stocker le nouvel objet PDO et sera disponible dans toute page qui inclut ce fichier via require_once. "new" ordonne de construire un nouvel objet. "PDO" est la classe PHP à partir de laquelle on veut construire le nouvel objet. "new PDO" est l'instanciation de la classe, c'est-à-dire la création de l'objet PDO :
    $pdo = new PDO(

        // Data Source Name ou DSN : c'est le premier argument lors de la création de la connexion. mysql (type de base qu'on veut utiliser), host (adresse de la machine sur laquelle tourne le serveur de base de données), dbname (nom de la base à laquelle je veux me connecter), charset (encodage). Ils sont récupérés depuis les variables d'environnement Docker :
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'] . ";charset=utf8mb4",
        
        // Identifiants utilisés pour se connecter à MySQL, ce sont les deux autres arguments qui doivent figurer dans cet ordre et on les récupère depuis les variables d'environnement Docker : 
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    // Afficher les erreurs SQL sous forme d'exceptions : on accède à PDO (l'objet instancié), on utilise la méthode setAttribute qui permet de modifier un réglage de la connexion (elle prend toujours deux arguments). PDO:: est une constante de la classe PDO. On passe donc en premier argument ATTR_ERRMODE, qui est l'attribut "mode de gestion des erreurs". Ensuite on lui donne la valeur souhaitée, ici ERRMODE_EXCEPTION (le script s'arrête immédiatement à l'endroit de l'erreur sauf si attrapé avec try/catch, c'est sûr et prévisible).
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cette ligne sert à dire que sur ma connexion active le mode de récupération des données par défaut doit être réglé sur "tableau associatif". Si je décompose, le début est comme la ligne précédente. En premier argument, l'attribut ATTR_DEFAULT_FETCH_MODE : Mode de récupération par défaut, c'est-à-dire comment les données doivent-elles être formatées quand je les récupère ? Le second argument est la valeur souhaitée, ici FETCH_ASSOC : récupère les données sous forme de tableau associatif :
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $error) { // Catch est le bloc qui s'exécute si une exception a été lancée dans le try, ici à cause d'une erreur de type PDOException. C'est une classe native de PHP qui hérite de la classe générique Exception. $error désigne l'exception attrapée à l'intérieur du bloc.
    // En production : on affiche l'erreur côté serveur pour qu'elle ne soit pas montrée à l'utilisateur (on n'expose pas les détails de la base de données):
    error_log($error->getMessage()); // Appel de la méthode getMessage() (héritée de la classe Exception) sur l'objet exception $error. Cela retourne la description textuelle de l'erreur.
    // die est une fonction native de PHP qui stoppe immédiatement l'exécution du script. Le message passé en argument est affiché à l'utilisateur. 
    die("Erreur de connexion.");
}
