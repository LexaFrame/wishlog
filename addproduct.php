<?php
session_start();
require_once 'includes/auth_check.php';
// ^Créer une session, à écrire en premier avant tout affichage HTML, sur la page de connexion 

require_once 'config/database.php';

$product_priority = null; // valeur par défaut
$product_description = null; // valeur par défaut
$product_quantity = 1; // valeur par défaut

// 1 - Détection du mode : update ou create ?
// Vérifie si le paramètre 'id' est présent dans l'URL (ex : addproduct.php?id=3), si oui, $product_edit_mode = true.
$product_edit_mode = isset($_GET['id']);

// 2 - Bloc de gestion de l'affichage pour UPDATE :
// 2-1 Initialisation de la variable à null, elle sera remplie par le résultat si on est en mode modification :
$edit_product_result = null;

// 2-2 Si $product_edit_mode est true, on doit récupérer l'id puis SELECT pour récupérer les données existantes. S'effectue au chargement de la page :
if ($product_edit_mode) {
    // Récupération de l'id du produit qu'on veut modifier depuis l'URL :
    $product_id_edit = $_GET['id'];

    // 2-3 Requête pour récupérer les informations du produit que l'on souhaite modifier et les afficher dans le formulaire :
    $edit_product = $pdo->prepare(
        "SELECT product_name, shop_name, product_url, product_image_url, product_description, product_price, product_priority, product_quantity, category_name
        FROM wl_product
        INNER JOIN wlwishlist_wlproduct ON wlwishlist_wlproduct.product_id= wl_product.product_id
        LEFT JOIN wl_category ON wl_product.category_id = wl_category.category_id 
        WHERE wl_product.product_id = :product_id"
    );

    // 2-4 Exécution de la requête d'affichage des informations du produit existant qu'on souhaite modifier :
    $edit_product->execute(
        [':product_id'=>$product_id_edit]
    );

    // 2-5 Récupérer le résultat d'$edit_product :
    $edit_product_result = $edit_product->fetch();
}

// 3 - Bloc commun à CREATE et UPDATE : vérification que le formulaire a bien été soumis en POST, + vérifications que les champs obligatoires sont bien remplis avant de traiter.
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titleAddProduct'], $_POST['priceAddProduct'], $_POST['quantityAddProduct'], $_POST['linkAddProduct'])) {
    // On récupère les valeurs depuis le formulaire et on les nettoie avec trim() :
    $product_name = trim($_POST['titleAddProduct']);
    $shop_name = trim($_POST['shopAddProduct'] ?? '');
    $product_price = trim($_POST['priceAddProduct']);

    // On récupère également les valeurs non obligatoires du formulaire et on les nettoie avec trim() :
    //max(1,...) garantit que la quantité ne peut jamais être inférieure à 1 quelle que soit la valeur envoyée :
    $product_quantity = max(1, (int) ($_POST['quantityAddProduct'] ?? 1));

    // 3-1 - Utilisation de $has_errors pour servir d'alerte pour que dès qu'une validation échoue on le passe à true pour empêcher le reste du traitement de s'exécuter : 
    $has_errors = false;

    // 3-2 - Utilisation de str_replace() pour supprimer les espaces (ex: 2 304,50) en prévision de l'utilisation de is_numeric qui sinon renverrait false :
    $product_price = str_replace(' ', '', trim($_POST['priceAddProduct']));

    // 3-3 - Utilisation de str_replace() pour remplacer les virgules par des points en prévision de l'utilisation de is_numeric pour interpréter correctement les nombres décimaux :
    $product_price = str_replace(',', '.', $product_price);

    // 3-4 - Vérification de la saisie utilisateur dans le champ prix à l'aide de is_numeric() :
    if (!is_numeric($product_price) || $product_price <= 0) {
        $product_price_format_error_message = "Votre saisie est invalide. Veuillez saisir un prix valide supérieur à 0.";
        $has_errors = true;
    }

    // TODO : Gestion du nom de catégorie reportée pour une fonctionnalité future $category_name = trim($_POST['categoryAddProduct'] ?? '');

    // 3-6 Récupération de la priorité depuis le formulaire :
    $product_priority = $_POST['priorityAddProduct'] ?? null;

// 3-7 Si l'utilisateur a laissé l'option par défaut "selection" ou n'a rien sélectionné, on stocke NULL pour éviter d'insérer une chaîne non numérique dans la base de données :
if ($product_priority === "selection") {
    $product_priority = null; // valeur par défaut acceptable
}
    // 3-8 Gestion description : Si la description est vide (ou ne contient que des espaces), on la met à NULL pour éviter d'insérer une chaîne vide inutile dans la base. L'opérateur "?? ''" permet de mettre une chaîne vide si le champ n'existe pas dans $_POST.
    $product_description = trim($_POST['descriptionAddProduct'] ?? '');
    if ($product_description === '') {
        $product_description = null;
    }

    $product_image_url = trim($_POST['imageAddProduct'] ?? '');
    $product_url = trim($_POST['linkAddProduct']); 

    // 3-9 Valeurs fixes côté serveur (pour l'instant $product_origin est toujours 'manual' et la gestion des catégories est reportée):
    $product_origin = 'manual';
    $category_id = null;

    // 4- Une fois toutes les validations effectuées ci-dessus, on vérifie $has_errors. Si une erreur a été détectée : on n'entre pas dans le bloc et le traitement s'arrête. Si aucune erreur n'a été détectée on continue vers les vérifications en base de données et l'insertion :
    if (!$has_errors) {

        // 5- Bloc de gestion de la modification effective du produit pour UPDATE :
        // 5-1 Si mode modification :
        if ($product_edit_mode) {
            try {
                // 5-2 Préparation de la 1ère requête pour modification des informations du produit dans wl_product :
                $modify_product = $pdo->prepare(
                    "UPDATE `wl_product`
                    SET product_url = :product_url, shop_name = :shop_name, product_name = :product_name, product_image_url = :product_image_url, product_description = :product_description, product_price = :product_price, product_priority = :product_priority, product_origin = :product_origin, category_id = :category_id
                    WHERE product_id = :product_id"
                );

                // 5-3 Exécution de la 1ère requête de modification :
                $modify_product->execute(
                    [':product_url'=>$product_url,':shop_name'=>$shop_name,':product_name'=>$product_name,':product_image_url'=>$product_image_url,':product_description'=>$product_description,':product_price'=>$product_price,':product_priority'=>$product_priority,':product_origin'=>$product_origin,':category_id'=>$category_id,':product_id' => $product_id_edit]
                );

                // 5-4 Préparation de la 2e requête pour modification des informations du produit dans wlwishlist_wlproduct :
                $modify_product = $pdo->prepare(
                    "UPDATE `wlwishlist_wlproduct`
                    SET product_quantity = :product_quantity
                    WHERE product_id = :product_id"
                );

                // 5-5 Exécution de la 2e requête de modification :
                $modify_product->execute(
                    [':product_quantity'=>$product_quantity,':product_id' => $product_id_edit]
                );

                // 5-6 Message à destination de l'utilisateur pour l'informer du succès de la modification :
                $update_success_message = "Le produit a été modifié avec succès.";
            
            } catch (PDOException $error) {
                // 5-7 Gestion des erreurs éventuelles lors de la soumission de la modification :
                // Utilisation d'error_log() qui est une fonction native PHP qui écrit un message d'erreur dans le fichier de log du serveur. Cela permet d'enregistrer les erreurs techniques sans les afficher à l'utilisateur qui ne voit que le message générique. L'objectif est de ne révéler aucune information sensible sur la base de données. getMessage() est une méthode de la classe Exception qui retourne le message textuel décrivant l'erreur.
                error_log($error->getMessage());
                // 5-8 Message à destination de l'utilisateur pour l'informer de l'échec de la modification :
                $update_error_message = "La modification du produit a échoué.";
            }

        } else {
            // 6- Bloc de gestion du CREATE : 
            try {

                // 6-1 Vérification du wishlist_id existant avec une requête SQL préparée :
                $retrieve_wishlist_id = $pdo->prepare(
                    "SELECT wishlist_id
                    FROM wluser_wlwishlist
                    WHERE user_id = :user_id"
                );

                $retrieve_wishlist_id->execute(
                    [':user_id'=>$_SESSION['user_id']]
                );

                // 6-2 Récupération du résultat de la requête :
                $retrieve_wishlist_id_result = $retrieve_wishlist_id->fetch();

                $current_wishlist_id = $retrieve_wishlist_id_result['wishlist_id'];
            
                // 6-3 Préparation de la requête pour l'insertion des informations du produit dans wl_product :
                $insert_product = $pdo->prepare(
                    "INSERT INTO `wl_product` (product_url, shop_name, product_name, product_image_url, product_description, product_price, product_priority, product_origin, category_id)
                    VALUES
                    (:product_url, :shop_name, :product_name, :product_image_url, :product_description, :product_price, :product_priority, :product_origin, :category_id)"
                );

                // 6-4 Exécution de la requête :
                $insert_product->execute(
                    [':product_url'=>$product_url,':shop_name'=>$shop_name,':product_name'=>$product_name,':product_image_url'=>$product_image_url,':product_description'=>$product_description,':product_price'=>$product_price,':product_priority'=>$product_priority,':product_origin'=>$product_origin,':category_id'=>$category_id]
                );

                // 6-5 Récupération de l'id généré par l'insertion précédente :
                $product_id = $pdo->lastInsertId();

                // 6-6 Seconde requête liée à la table wlwishlist_wlproduct :
                $insert_wishlist_product = $pdo->prepare(
                    "INSERT INTO `wlwishlist_wlproduct`(wishlist_id, product_id, product_quantity)
                    VALUES
                    (:wishlist_id, :product_id, :product_quantity)"
                );

                // 6-7 Exécution de la seconde requête :
                $insert_wishlist_product->execute(
                    [':wishlist_id'=>$current_wishlist_id,':product_quantity'=>$product_quantity, ':product_id'=>$product_id]
                );

                // 6-8 Message à destination de l'utilisateur pour l'informer du succès de l'insertion : 
                $product_success_message = "Le produit a été ajouté à votre liste d'envies avec succès !";

            } catch (PDOException $error) {
                // 6-9 Gestion des erreurs éventuelles lors de la soumission de l'ajout :
                // Utilisation d'error_log() qui est une fonction native PHP qui écrit un message d'erreur dans le fichier de log du serveur. Cela permet d'enregistrer les erreurs techniques sans les afficher à l'utilisateur qui ne voit que le message générique. L'objectif est de ne révéler aucune information sensible sur la base de données. getMessage() est une méthode de la classe Exception qui retourne le message textuel décrivant l'erreur.
                error_log($error->getMessage());
                // 6-10 Message à destination de l'utilisateur pour l'informer de l'échec de l'insertion : 
                $product_error_message = "L'ajout du produit a échoué.";
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
        <!-- Introduction à la page Ajout Article -->
        <section class="addProductSection">

        <!--TODO modifier noms de classe-->

            <!-- Div à rétablir si nécessaire <div class="contactPageIntro"> -->
                
                <!-- Titre de la page -->
                 <!-- Adaptation du titre de la page grâce à l'opérateur ternaire ?: pour if/else -->
                <!-- TODO décommenter et récupérer dans le CSS les règles correspondantes qui ont disparu <div class="pageTitleBox"> --> 
                    <h1 class="h1AddProduct"><?php echo $product_edit_mode ? 'Modifiez un produit' : 'Ajoutez un produit'; ?></h1>
                <!-- </div> -->

                <!-- Paragraphe ajout d'articles -->
                <div class="pageParagraphBox">
                    <p class="addProductP"><?php echo $product_edit_mode ? 'Modifiez les informations du produit.' : 'Saisissez les informations du produit que vous souhaitez ajouter à votre liste.'; ?></p>
                </div>

            <!-- </div> -->

        </section>
        <!-- Formulaire d'ajout d'articles' -->
        <section class="addProductFormSection">

        <!-- Affichage des messages de succès ou d'erreurs pour la modification ou l'insert d'un produit dans la liste d'envies -->
        <?php if (isset($update_success_message)) : ?>
            <p class="updateProductFormSuccess"><?php echo htmlspecialchars($update_success_message); ?></p>
        <?php endif; ?>

        <?php if (isset($update_error_message)) : ?>
            <p class="updateProductFormError"><?php echo htmlspecialchars($update_error_message); ?></p>
        <?php endif; ?>

        <?php if (isset($product_success_message)) : ?>
            <p class="productFormSuccess"><?php echo htmlspecialchars($product_success_message); ?></p>
        <?php endif; ?>

        <?php if (isset($product_error_message)) : ?>
            <p class="productFormError"><?php echo htmlspecialchars($product_error_message); ?></p>
        <?php endif; ?>

            <!-- TODO Début formulaire -> à adapter-->           
            <div class="formCard">
            <form class="form" method="POST" action="addproduct.php">

            <!-- Champs de détail des articles -->
                <!-- Champ titre -->
              <div class="titleAddProductBlock">
                  <label for="titleAddProduct">Titre<span class="required"> *</span></label>
                  <!-- Avec value, utilisation d'un opérateur ternaire qui remplace le if/else classique pour remplir le champ du formulaire si on est en mode modification -->
                  <input type="text" id="titleAddProduct" class="inputFields" name="titleAddProduct" placeholder="" value="<?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['product_name'] ?? '') : ''; ?>" required>
              </div>

                <!-- Champ nom du magasin -->
              <div class="shopAddProductBlock">
                  <label for="shopAddProduct">Boutique</label>
                  <input type="text" id="shopAddProduct" class="inputFields" name="shopAddProduct" placeholder="" value="<?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['shop_name'] ?? '') : ''; ?>">
              </div>

                <!-- Champ prix -->
              <div class="priceAddProductBlock">
                  <label for="priceAddProduct">Prix en €<span class="required"> *</span></label>
                  <input type="text" id="priceAddProduct" class="inputFields" name="priceAddProduct" placeholder="" min="0.01" step="0.01" value=" <?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['product_price'] ?? '') : ''; ?>" required>
              </div>
              <?php if(isset($product_price_format_error_message)) : ?>
                <p class="productFormError"><?php echo htmlspecialchars($product_price_format_error_message); ?></p>
              <?php endif; ?>

                <!-- Champ quantité -->
              <div class="quantityAddProductBlock">
                  <label for="quantityAddProduct">Quantité</label>
                  <input type="number" id="quantityAddProduct" class="inputFields" name="quantityAddProduct" min="1" step="1" value="<?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['product_quantity'] ?? '') : '1'; ?>">
              </div>                

                <!-- Sélection catégorie -->              
                <div class="categoryAddProductBlock">
                    <label for="categoryAddProduct">Catégorie</label>
                    <select id="categoryAddProduct" name="categoryAddProduct">
                        <option value="selection">-- Sélectionnez une catégorie --</option>
                        <option value="newCategory">Nouvelle catégorie</option>
                    </select>  
                </div>

                <!-- Sélection priorité -->              
                <div class="priorityAddProductBlock">
                    <label for="priorityAddProduct">Priorité</label>
                    <select id="priorityAddProduct" name="priorityAddProduct">
                        <option value="selection">-- Sélectionnez un niveau de priorité --</option>
                        <option value="1" <?php echo ($product_edit_mode && $edit_product_result['product_priority'] == 1) ? 'selected' : ''; ?>>Priorité : Très haute</option>
                        <option value="2"<?php echo ($product_edit_mode && $edit_product_result['product_priority'] == 2) ? 'selected' : ''; ?>>Priorité : Haute</option>
                        <option value="3"<?php echo ($product_edit_mode && $edit_product_result['product_priority'] == 3) ? 'selected' : ''; ?>>Priorité : Moyenne</option>
                        <option value="4"<?php echo ($product_edit_mode && $edit_product_result['product_priority'] == 4) ? 'selected' : ''; ?>>Priorité : Basse</option>
                        <option value="5"<?php echo ($product_edit_mode && $edit_product_result['product_priority'] == 5) ? 'selected' : ''; ?>>Priorité : Très basse</option>                                                                        
                    </select>  
                </div>

                <!-- Zone de saisie de la description du produit -->
              <div class="descriptionAddProductBlock">
                <label for="descriptionAddProduct" class="descriptionAddProductLabel">Description du produit</label>
                <textarea id="descriptionAddProduct" name="descriptionAddProduct" class="inputFields" placeholder="Entrez votre message..." rows="5" cols="30"><?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['product_description'] ?? '') : ''; ?>
                </textarea>
              </div>

                <!-- Champ pour charger une image du produit-->
              <div class="imageAddProductBlock">
                  <label for="imageAddProduct">Lien vers l'image du produit</label>
                  <input type="text" id="imageAddProduct" class="inputFields" name="imageAddProduct" placeholder="" value="<?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['product_image_url'] ?? '') : ''; ?>">
              </div>

                <!-- Champ lien vers le produit-->
              <div class="linkAddProductBlock">
                  <label for="linkAddProduct">Lien vers le produit<span class="required"> *</span></label>
                  <input type="text" id="linkAddProduct" class="inputFields" name="linkAddProduct" placeholder="" value="<?php echo $product_edit_mode ? htmlspecialchars($edit_product_result['product_url'] ?? '') : ''; ?>" required>
              </div>

            <!-- Bouton d'envoi qui s'adapte grâce à l'opérateur ternaire ?: -->
            <div class="submitFormButton">
            <button class="submitButton" type="submit"><?php echo $product_edit_mode ? 'Modifier' : 'Ajouter'; ?></button>
            </div>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>


  </body>
  </html>