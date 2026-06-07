<?php
session_start();
// require_once 'includes/auth_check.php';
// ^Créer une session, à écrire en premier avant tout affichage HTML, sur la page de connexion

// Établir la connexion entre la page et le fichier database.php :
require_once 'config/database.php';

// Récupérer l'identifiant de l'utilisateur connecté :
$user_id = $_SESSION['user_id'];

// 1 - Vérification que le formulaire a bien été soumis en POST, + vérifications que les champs obligatoires sont bien remplis avant de traiter:
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titleCreateWishlist'], $_POST['typeOfWishlist'])) {

    // 2-1 On récupère et on stocke dans une variable les valeurs des champs obligatoires depuis le formulaire et on nettoie ces données avec trim() :
    $wishlist_title = trim($_POST['titleCreateWishlist']);
    $wishlist_type = trim($_POST['typeOfWishlist']);

    // 2-2 On récupère aussi les valeurs des champs non-obligatoires depuis le formulaire et on les nettoie avec trim() :
    $wishlist_date = trim($_POST['dateCreateWishlist'] ?? '');
    $wishlist_description = trim($_POST['descriptionCreateWishlist'] ?? '');

    // 2-3 - Utilisation de $has_errors pour servir d'alerte pour que dès qu'une validation échoue on le passe à true pour empêcher le reste du traitement de s'exécuter :
    $has_errors = false;

    // 2-4 Vérification que l'utilisateur a sélectionné un type de liste autorisé.
    
    // 2-4-1 On crée un tableau regroupant les valeurs autorisées venant du menu déroulant de type de liste d'envies pour empêcher qu'un utilisateur malveillant remplace la sélection par autre chose : 
    $allowed_types = ['birthday', 'gift', 'holidays', 'wedding', 'baby', 'wishlist'];

    // 2-4-2 Vérification que la valeur reçue depuis le formulaire fait bien partie de la liste des valeurs autorisées :
    if (!in_array($wishlist_type, $allowed_types, true)) {
        $create_wishlist_type_error_message = "Veuillez sélectionner un type de liste valide.";
        $has_errors = true;
    }

    // 2-5 Convertir l'état de la case à cocher de $wishlist_surprise en 1 (cochée) ou 0 (non cochée) grâce à un opérateur ternaire :
    $wishlist_surprise = isset($_POST['keepSurprise']) ? 1 : 0;

    // 4 - Vérification du format pour le champ date de l'évènement :

    // TODO
    
    // 5 - Une fois toutes les validations effectuées ci-dessus, on vérifie $has_errors. Si une erreur a été détectée : on n'entre pas dans le bloc et le traitement s'arrête. Si aucune erreur n'a été détectée on continue vers les vérifications en base de données et l'insertion :
    if (!$has_errors) {

        // 6 - Vérification que le titre de la wishlist n'est pas déjà pris dans le compte utilisateur :
        
        // 6-1 Requête pour comparer le titre de la wishlist et l'identifiant utilisateur afin de vérifier que le titre n'est pas déjà pris dans la base de données pour cet utilisateur :
        $create_new_wishlist_attempt = $pdo->prepare(
            "SELECT wl_wishlist.wishlist_id
            FROM wl_wishlist
            INNER JOIN wluser_wlwishlist
            ON wl_wishlist.wishlist_id = wluser_wlwishlist.wishlist_id
            WHERE wl_wishlist.wishlist_name = :wishlist_name
            AND wluser_wlwishlist.user_id = :user_id"
        );

        // 6-2 Exécution de la requête de vérification du titre de la wishlist :
        $create_new_wishlist_attempt->execute(
            [':wishlist_name' => $wishlist_title, ':user_id' => $_SESSION['user_id']]
        );

        // 6-3 Récupérer le résultat de $create_new_wishlist_attempt :
        $create_new_wishlist_result = $create_new_wishlist_attempt->fetch();

        // 6-4 Si fetch renvoie des données concernant le nom d'utilisateur, message d'erreur :
        if ($create_new_wishlist_result !== false) {
            $create_wishlist_error_message = "Erreur : une wishlist portant le même titre existe déjà sur votre compte. Veuillez saisir un autre titre ou supprimer l'ancienne liste avant de tenter de créer une liste portant le même titre.";
        } else {
            // 7 - Insertion des données dans la base de données avec try/catch au cas où il y ait une erreur pour accéder à la base :
            try {
                // 7-1 Préparation de la requête pour l'insertion des données de la nouvelle wishlist dans la table wl_wishlist :
                $insert_new_wishlist = $pdo->prepare(
                    "INSERT INTO `wl_wishlist`
                    (wishlist_name, event_type, event_date, wishlist_description, hide_purchases)
                    VALUES (:wishlist_name, :event_type, :event_date, :wishlist_description, :hide_purchases)"
                );

                // 7-2 Exécution de la requête :
                $insert_new_wishlist->execute(
                    [':wishlist_name' => $wishlist_title, ':event_type' => $wishlist_type, ':event_date' => $wishlist_date, ':wishlist_description' => $wishlist_description, ':hide_purchases' => $wishlist_surprise]
                );

                // 7-3 On récupère l'identifiant de la wishlist créé :
                $new_wishlist_id = $pdo->lastInsertId();

                $insert_created_wishlist_link = $pdo->prepare(
                    "INSERT INTO `wluser_wlwishlist`
                    (user_id, wishlist_id, role_in_wishlist)
                    VALUES (:user_id, :wishlist_id, :role_in_wishlist)"
                );

                $insert_created_wishlist_link->execute(
                    [':user_id' => $user_id, ':wishlist_id' => $new_wishlist_id, ':role_in_wishlist' => 'owner']
                );

                // 7-5 Mise en place d'un message flash : c'est un message de succès qui sera stocké temporairement dans la session, le temps de l'afficher sur la page suivante.
                
                // à vérifier : $_SESSION['createWishlistSuccess'] = "Votre liste a été créée avec succès. Vous pouvez maintenant l'utiliser.";

                // 8 - Redirection de l'utilisateur vers la page wishlist.php :
                header('Location: wishlist.php');

                // 9 - Arrêt de l'exécution du code PHP pour éviter de causer des comportements inattendus si PHP continue d'exécuter le code après la redirection :
                exit();

                // 10 - Gestion des erreurs avec le type d'erreur PDOException (erreurs liées à la base de données) & $error pour récupérer le message d'erreur technique :
            } catch (PDOException $error) {
                // 11-1 Gestion des erreurs éventuelles lors de la soumission de l'ajout : utilisation d'error_log() qui est une fonction native PHP qui écrit un message d'erreur dans le fichier de log du serveur. Cela permet d'enregistrer les erreurs techniques sans les afficher à l'utilisateur qui ne voit que le message générique. L'objectif est de ne révéler aucune information sensible sur la base de données. getMessage() est une méthode de la classe Exception qui retourne le message textuel décrivant l'erreur.
                error_log($error->getMessage());

                // 11-2 Message à destination de l'utilisateur pour l'informer de l'échec de l'insertion :
                    $create_new_wishlist_final_error_message = "Une erreur est survenue. La liste d'envies n'a pas pu être créée.";
            }
        }
    }

}
?>

<!--
  Author: Sarah Segui Bilger
  Project: WishLog
  Context: Educational project – public repository required by training
-->

<!DOCTYPE html>
<html lang="fr">

<!-- Utilisation de require_once pour inclure le head (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
<?php require_once 'includes/head.php';?> 

  <body>
    <!--Début du code du contenu de la page-->

    <!-- Utilisation de require_once pour inclure le header (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/header.php';?>


    <main>
        <!-- Introduction à la page de création de liste d'envies -->
        <section class="createWishlistSection">

        <!--TODO modifier noms de classe-->

            <!-- Div à rétablir si nécessaire <div class="contactPageIntro"> -->
                
                <!-- Titre de la page -->
                <!-- TODO décommenter et récupérer dans le CSS les règles correspondantes qui ont disparu <div class="pageTitleBox"> --> 
                    <h1 class="h1CreateWishlist">Créez une nouvelle liste d'envies</h1> <!-- TODO changer la classe -->
                <!-- </div> -->

                <!-- Paragraphe création d'une nouvelle liste d'envies -->
                <div class="pageParagraphBox">
                    <p class="addWishlistP">Saisissez les informations nécessaires afin de créer votre nouvelle liste.</p>
                </div>

            <!-- </div> -->

        </section>
        <!-- Formulaire de création de liste -->
        <section class="createWishlistFormSection">

            <!-- TODO Début formulaire -> à adapter-->           
            <div class="formCard">
            <form class="form" method="POST" action="createwishlist.php">

            <!-- Champs de détail de la liste d'envies -->
                <!-- Champ titre -->
              <div class="titleCreateWishlistBlock">
                  <label for="titleCreateWishlist">Titre<span class="required"> *</span></label>
                  <input type="text" id="titleCreateWishlist" class="inputFields" name="titleCreateWishlist" placeholder="" required>
              </div>

                <!-- Champ date -->
              <div class="dateCreateWishlistBlock">
                  <label for="dateCreateWishlist">Date de l'évènement</label>
                  <input type="text" id="dateCreateWishlist" class="inputFields" name="dateCreateWishlist" placeholder="">
              </div>

                <!-- Sélection type d'évènement -->                          
                <div class="typeOfWishlistBlock">
                    <label for="typeOfWishlist">Type d'évènement<span class ="required"> *</span></label>
                    <select id="typeOfWishlist" name="typeOfWishlist" required>
                        <option value="" selected disabled>-- Sélectionnez un type de liste --</option>
                        <option value="birthday">Anniversaire</option>
                        <option value="gift">Cadeaux</option>
                        <option value="holidays">Fêtes</option>
                        <option value="wedding">Mariage</option>
                        <option value="baby">Naissance</option>
                        <option value="wishlist">Achat</option>                                   
                    </select>  
                </div>
                <?php if (isset($create_wishlist_type_error_message)) : ?>
                    <p class="createWishlistError"><?php echo htmlspecialchars($create_wishlist_type_error_message); ?></p>
                    <?php endif; ?>


                <!-- Zone de saisie de la description de la liste -->
              <div class="descriptionCreateWishlistBlock">
                <label for="descriptionCreateWishlist" class="descriptionCreateWishlistLabel">Description de la liste d'envies</label><br>
                <textarea id="descriptionCreateWishlist" name="descriptionCreateWishlist" class="inputFields" placeholder="Entrez votre message..." rows="5" cols="30">
                </textarea>
              </div>

                <!-- Case à cocher "ne pas me gâcher la surprise -->
              <div class="keepSurpriseBlock">
                  <label for="keepSurprise">Ne pas me gâcher la surprise</label>
                  <input type="checkbox" id="keepSurprise" class="inputFields" name="keepSurprise" placeholder="">
              </div>

            <!-- Bouton d'envoi -->
            <div class="submitFormButton">
            <button class="submitButton" type="submit">Ajouter</button>
            </div>
            <?php if (isset($create_new_wishlist_final_error_message)) : ?>
                <p class="createWishlistError"><?php echo htmlspecialchars ($create_new_wishlist_final_error_message); ?></p>
                <?php endif; ?>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>


  </body>
  </html>