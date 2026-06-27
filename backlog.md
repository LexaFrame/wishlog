Plusieurs axes d’amélioration ont été identifiés au cours du développement de Wishlog, qu’il s’agisse de finaliser des fonctionnalités prévues dès la conception ou d’approfondir certaines bonnes pratiques. 

a)	 Front-end
•	Menu burger : un menu responsive en Flexbox a été privilégié pour répondre à une contrainte d'un précédent ECF. Une version ultérieure prévoira un menu burger pour la version mobile, plus intuitif et ergonomique pour les utilisateurs ;
•	Adaptation du menu de navigation selon l’état de connexion : les liens createwishlist.php, addproduct.php et wishlist.php sont actuellement visibles dans le menu quel que soit l'état de connexion. Bien qu'un visiteur non connecté soit redirigé vers login.php par auth_check.php, un menu adapté à l'état de connexion réduirait la charge cognitive de l'utilisateur ;
•	Renforcement du focus visuel : un style de focus plus marqué sur l'ensemble des éléments interactifs améliorerait la navigation au clavier, notamment pour les utilisateurs porteurs de handicap ;
•	Lisibilité de la police pour les utilisateurs porteurs de handicap : une réflexion complémentaire sur la distinction visuelle entre certains caractères ambigus (I majuscule et l minuscule) reste à approfondir.

b)	 JavaScript
•	Filtrage par catégorie fonctionnel : la logique de filtrage côté client, initialement développée avec wishlist.js à partir d’un fichier JSON n’est plus connectée depuis le passage au rendu PHP/PDO : une réintégration serait nécessaire ;
•	Tri des produits : les options de tri (prix, priorité, date d’ajout, etc.) sont présentes dans le formulaire mais non encore implémentées ;
•	Drag and drop : la réorganisation manuelle des produits dans une liste par glisser-déposer ou saisie d’un numéro de position n’est pas encore développée ;
•	Modification d’un produit : la modification se fait actuellement sur la même page que l'ajout ; l'ouverture d'une fenêtre dédiée lors du clic sur « Modifier » est envisagée.

c)	 PHP
•	Barre de recherche fonctionnelle : la zone de recherche nécessiterait une requête SQL côté serveur pour interroger listes correspondant à la saisie de l'utilisateur ou une recherche d’information concernant le site ;
•	Système de changement de langue : le sélecteur de langue FR/EN, actuellement non connecté, nécessiterait la mise en place soit d'un système de traduction du contenu ou une duplication de pages par langue mais il existe dans ce second cas un risque d’incohérences ;
•	Vérification des doublons de produits : aucune vérification n’empêche actuellement l’ajout d’un produit identique (identifiable par exemple par son URL) plusieurs fois dans une liste ;
•	Gestion fine des droits de suppression : la suppression manuelle (plutôt que ON DELETE CASCADE) a été choisie pour permettre, à terme, une vérification des droits du co-éditeur avant suppression, ainsi qu’une protection ou une notification par e-mail si un produit a déjà été choisi par un visiteur (buy_decision) ;
•	Confirmation d’inscription par e-mail : prévue mais non implémentée à ce stade ;
•	Conservation des données saisies en cas d’erreur : actuellement, les champs déjà correctement remplis ne sont pas conservés à l'affichage après une erreur de validation, obligeant l'utilisateur à tout ressaisir. Le ré-affichage des valeurs valides après une tentative échouée améliorerait l'expérience utilisateur ;
•	Co-gestion des listes : l’association manage entre utilisateurs et listes (table wluser_wlwishlist), porteuse des attributs role_in_wishlist et date_joined_wishlist prévoit la possibilité pour un utilisateur d’inviter d’autres personnes à co-gérer sa liste, avec des rôles différenciés (propriétaire, co-éditeur). Cette fonctionnalité n’a pas encore été implémentée ;
•	Gestion des réservations : l’association contain (table wlwishlist_wlproduct) porteuse des attributs product_quantity, buy_decision et purchase_cancelled prévoit la possibilité pour un visiteur de réserver l’achat d’un produit et d’annuler cette réservation. Cette fonctionnalité est présente dans la structure de données mais non encore connectée à une logique applicative ;
•	Multi-appartenance des produits : la possibilité pour un produit de figurer simultanément dans plusieurs listes par le biais d’une duplication mais aussi la possibilité de déplacer un produit d’une liste vers une autre reste à développer bien que le schéma de la base de données le permette déjà.
•	Récupération de mot de passe : génération d’un token de réinitialisation, envoi d’un e-mail, formulaire de changement de mot de passe ;
•	Modification et suppression de compte ;
•	Partage de liste par URL/e-mail/réseaux sociaux : génération d’un lien unique, éventuellement public sans authentification, envoi d’e-mail ;
•	Fonctionnalité « ne pas gâcher la surprise » : logique conditionnelle pour masquer certaines informations selon qui visite la liste ;
•	Récupération automatique des informations produit depuis une URL externe : exploitation des balises Open Graph présentes dans le <head> des pages produit, une solution gratuite, légale et stable dans le temps. D'autres approches seraient étudiées si Open Graph s'avérait insuffisant, en conservant systématiquement la possibilité pour l'utilisateur d'ajouter un produit manuellement en cas d'échec de la récupération automatique ;
•	Tableau de bord pour gérer les listes : visualisation, suppression, statut public/privé, paramétrage. L’ajout de statut public/privé nécessiterait une modification de la base de données ;
•	Personnalisation de l’interface (photo, description, peut-être URL personnalisée) : nécessiterait la gestion de l’upload d’image, la génération d’URL personnalisée et une évolution du schéma de la base de données.
Le projet a été développé en PHP procédural : le code est organisé en une suite d’instructions et de fonctions indépendantes exécutées de façon séquentielle, avec une utilisation de la programmation orientée objet limitée à la manipulation des objets PDO (sans création de classes propres). Une évolution vers une architecture orientée objet est envisagée :
•	Une classe « User » regroupant les méthodes register(), login(), logout() pour centraliser la logique actuellement dispersée entre signup.php et login.php ;
•	Une classe « Wishlist » avec des méthodes telles que create(), getByUser() pour récupérer les listes d’envies liées à un utilisateur ou encore addproduct(), et deleteProduct() ;
•	Une classe « Database » pour centraliser la gestion de la connexion PDO, actuellement gérée par une simple inclusion de fichier.
À plus long terme, une migration vers une architecture MVC (Modèle-Vue-Contrôleur) est envisagée : 
•	Les modèles (UserModel.php, ProductModel.php, WishlistModel.php) regrouperaient les requêtes SQL et la logique de données ;
•	Les vues se limiteraient à l’affichage HTML des données transmises ;
•	Les contrôleurs (WishlistController.php, ProductController.php) géreraient la logique de traitement (validation, appel des modèles, redirection) ;
•	Un routeur central (index.php) recevrait l’ensemble des requêtes pour les distribuer au bon contrôleur.

d)	Sécurité
•	Gestion des rôles (Role Based Access Control) : la  table wl_role prévoit actuellement deux rôles (user et admin) mais aucune logique de contrôle d’accès différencié n’est implémentée. Tous les comptes créés se voient attribuer le rôle user par défaut ;
•	Protection CSRF : absence de token CSRF sur les formulaires, exposant l’application à des requêtes frauduleuses initiées depuis un site tiers ;
•	Protection contre la force brute : absence de limitation des tentatives de connexion ;
•	Double authentification : non implémentée ;
•	Journalisation et alertes de sécurité : error_log() enregistre les erreurs techniques mais aucun système structuré de détection d’évènements suspects (tentatives de connexion répétées, par exemple) n’est en place ;
•	Protection des dossiers techniques : les dossiers config/ et includes/ ne disposent pas de protection explicite contre un accès direct par URL ;
•	Vérification des dépendances : bien que le projet n’utilise pas de dépendances externes via Composer à ce stade, une vigilance serait nécessaire en cas d’ajout futur de bibliothèques tierces ;
•	Veille de sécurité élargie : au-delà du Top 10 OWASP, une veille plus large pourrait s’appuyer sur les recommandations de l’ANSSI, notamment pour le renforcement des politiques de mots de passe et la gestion des sessions.

e)	 SEO
•	Personnalisation des balises meta par page : le <title> et la <meta name=’’description’’> sont actuellement définis dans le fichier head.php centralisé et donc identiques sur l’ensemble des pages. Une solution envisagée consiste à définir des variables PHP ($page_title, $page_description) avant l’inclusion du head sur chaque page, avec des valeurs par défaut dans head.php. Cette personnalisation ne viserait pas un gain de classement direct mais une amélioration du taux de clic (CTR) depuis les résultats de recherche, lui-même pris en compte indirectement par Google ;
•	Optimisation des performances : l’audit Lighthouse révèle des scores de performance plus faibles sur wishlist.php et sur les pages de formulaires en version mobile. Les pistes identifiées incluent l’ajout d’attributs width/height explicites sur les images pour éviter les décalages de mise en page, la mise en place du chargement différé sur les images de produits et l’optimisation du fichier CSS unique.

f)	 Tests
•	Les tests unitaires automatisés n’ont pas encore été mis en place dans le cadre de ce projet, à la fois par manque de temps et parce que cette compétence n’était pas au cœur des objectifs de la formation. Ils constituent une amélioration future prioritaire, notamment pour tester de façon automatique les fonctions de validation des formulaires et les requêtes PDO. ;
•	Les tests de sécurité formalisés n’ont pu être effectués. Les mesures de sécurité mises en place (requêtes préparées, hashage, validation des entrées) sont des mesures préventives intégrées au développement, pas des tests a posteriori. Un audit de sécurité notamment basé sur l’OWASP Testing Guide serait souhaitable avant toute mise en production réelle.
