<?php
session_start();
require_once 'includes/auth_check.php';
// ^Créer une session, à écrire en premier avant tout affichage HTML, sur la page de connexion 

// Intégration de ma requête SQL pour afficher les produits dans la wishlist grâce à PDO :

// Établir la connexion entre la page et le fichier database.php :
require_once "config/database.php";

// Récupérer l'identifiant de l'utilisateur connecté :
$user_id = $_SESSION['user_id'];

// Récupérer l'id de la wishlist et le nom de la wishlist à l'aide d'une requête avec PDO :
$get_wishlist_id = $pdo->prepare(
    "SELECT wluser_wlwishlist.wishlist_id, wishlist_name
    FROM wluser_wlwishlist
    INNER JOIN wl_wishlist ON wluser_wlwishlist.wishlist_id = wl_wishlist.wishlist_id
    WHERE user_id = :user_id"
);

$get_wishlist_id->execute(
    [':user_id'=>$user_id]
);

// Récupérer le résultat de $get_wishlist_id :
$get_wishlist_id_result = $get_wishlist_id->fetch();

// Gestion des deux cas (redirection si pas de wishlist_id ou extraire le wishlist_id et le nom de la wishlist dans une variable) :
if ($get_wishlist_id_result === false) {
    // Redirection vers la page de création de liste d'envies :
    header('Location: createwishlist.php');

    // On arrête l'exécution du reste du code :
    exit();
} else {
    $wishlist_id = $get_wishlist_id_result['wishlist_id'];
    $wishlist_name = $get_wishlist_id_result['wishlist_name'];
}

// Vérifie si le bouton de suppression a été cliqué par l'utilisateur (permet de distinguer ce formulaire des autres formulaires de la page). Si oui, PHP reçoit la valeur "delete_action" dans $_POST :
if (isset($_POST['delete_action'])) {
  // Récupérer l'id du produit à supprimer qui vient du champ hidden du formulaire, grâce à $_POST :
  $product_id_delete = $_POST['delete_product_id'];

  try {
    // Préparation de la 1ère requête pour la suppression du produit :
    $delete_product1 = $pdo->prepare(
      "DELETE FROM wlwishlist_wlproduct WHERE product_id = :product_id;"
    );

    // Exécution de la 1ère requête de suppression du produit :
    $delete_product1->execute([':product_id'=>$product_id_delete]);

    // Préparation de la 2e requête pour la suppression du produit :
    $delete_product2 = $pdo->prepare(
      "DELETE FROM wl_product WHERE product_id = :product_id;"
    );

    // Exécution de la 2e requête de suppression du produit :
    $delete_product2->execute([':product_id'=>$product_id_delete]);

    // Message à destination de l'utilisateur pour l'informer du succès de la suppression :
    $delete_success_message = "Le produit a été supprimé.";

  } catch (PDOException $error) {
    // Message à destination de l'utilisateur pour l'informer du succès de la suppression :
    error_log($error->getMessage());
    $delete_error_message = "La suppression a échoué.";
  }
}

// Préparation de la requête :
$display_wishlist = $pdo->prepare(
    "SELECT product_name, shop_name, product_url, product_image_url, product_description, product_price, product_priority, wl_product.product_id, product_quantity, category_name
    FROM wl_wishlist 
    INNER JOIN wlwishlist_wlproduct ON wl_wishlist.wishlist_id = wlwishlist_wlproduct.wishlist_id 
    INNER JOIN wl_product ON wlwishlist_wlproduct.product_id= wl_product.product_id 
    LEFT JOIN wl_category ON wl_product.category_id = wl_category.category_id
    WHERE wl_wishlist.wishlist_id = :wishlist_id;"
);

// Exécution de la requête :
$display_wishlist->execute([':wishlist_id' => $wishlist_id]);


// Récupérer le résultat :
$final_display = $display_wishlist->fetchAll();

//Structure utilisée plus bas pour l'affichage des cartes, à supprimer dès que devenu inutile : 
/*foreach ($affichage_final as $products) {
  
}*/

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
        <!-- Titre de la page : affichage de la liste d'envies en vue propriétaire : -->
         <div class="pageTitleBox">
            <h1 class="h1Wishlist">Ma liste : <?php echo htmlspecialchars($wishlist_name); ?></h1>
        </div> 
        
        <!-- Bannière à thème de la liste -->
         <img src="images/achristmasgift.jpg" class="wishlistBanner" alt="Photo d'un paquet cadeau" />

         <!-- Description de la liste -->
         <section class="wishlistDescriptionSection">
            <p class="wishlistDescriptionParagraph">Bienvenue sur cette liste d'envies qui me permet de garder une trace d'objets qui me plaisent pour un futur achat et qui peut aussi aider les proches en mal d'inspiration.</p>
         </section>
         <?php if (isset($_SESSION['wishlist_created'])) : ?>
          <p class="createWishlistSuccess"><?php echo htmlspecialchars($_SESSION['wishlist_created']);
          // Utilisation de unset() qui supprime la clé "wishlist_created" de $_SESSION après l'avoir affichée pour ne pas que le message réapparaisse à chaque fois que l'utilisateur actualise la page :
          unset($_SESSION['wishlist_created']); ?></p>
          <?php endif; ?>

          <?php if (isset($_SESSION['update_success_message'])) : ?>
            <p class="updateProductFormSuccess"><?php echo htmlspecialchars($_SESSION['update_success_message']); ?></p>
            <?php unset($_SESSION['update_success_message']); ?>
          <?php endif; ?>

         <!-- Section rassemblant les données partagées par la personne ayant créé la liste -->
         <section class="personalDataSection">

            <!-- Date de l'évènement visé par la liste si applicable -->
            <div class="eventDataSection">
                <p class="eventDataParagraph">Date de l'évènement : 09/10/2026</p>
            </div>

            <!-- Séparateur -->
            <div class="divideSection">
                <span class="wishlistSeparation">|</span>
            </div>

            <!-- Identification et adresse de la personne si applicable -->
            <div class="idDataSection">
                <p class="idDataParagraph">Coordonnées : Jane Doe, rue du Développement, 000404, Webcity</p>
            </div>

         </section>
         
         <!-- Section avec cartes produit -->
         <section class="wishlistProductsSection">

            <!-- Création des menus déroulants pour filtrer -->
            <div class="filterBox">
                <label for="filterCategory" class="filterLabel">Catégorie</label>
                <select id="filterCategory" name="filterCategory">
                  <option value="selection">Catégories</option>
                  <option value="all">Toutes les catégories</option>                  
                </select>  

                <label for="sortItems" class="sortItemsLabel">Trier par :</label>
                <select id="sortItems" name="filterCategory">
                  <option value="selection">Trier par :</option>
                  <option value="priceUp">Prix croissant</option>
                  <option value="priceDown">Prix décroissant</option>
                  <option value="recentEntries">Date d'ajout la plus récente</option>
                  <option value="olderEntries">Date d'ajout la plus ancienne</option>
                  <option value="topPriority">Priorité de la plus haute à la plus faible</option>
                  <option value="lowPriority">Priorité de la plus faible à la plus haute</option>
                </select> 
            </div>
            
            <?php if (isset($delete_success_message)) : ?>
              <p class="productDeleteSuccess"><?php echo htmlspecialchars($delete_success_message); ?></p>
            <?php endif; ?>

            <?php if (isset($delete_error_message)) : ?>
              <p class="productDeleteError"><?php echo htmlspecialchars($delete_error_message); ?></p>
            <?php endif; ?>

            <!-- Conteneur des cartes produit -->
            <div class="wishlistProductsSectionCardBox">

              <!-- Début du foreach : pour chaque ligne dans $affichage_final on crée une carte. $products représente une ligne de résultats -->
              <?php foreach ($final_display as $products) : ?>

                <!-- Carte individuelle par produits -->
                <!-- Data-category sert au filtre JS -->
                <div class="productCard" data-category="<?php echo htmlspecialchars($products['category_name'] ?? ''); ?>"> <!-- Utilisation de l'opérateur ?? ou null coalescing operator" qui fournit une valeur par défaut si la variable est NULL : ?? '' signifie que si la variable est NULL, il faut utiliser une chaîne vide à la place. -->

                  <!-- Contenu de la carte -->

                  <!-- Image du produit -->
                  <div class="productCardImage">

                    <!-- Récupération de src et alt de l'image depuis la base de données, mise en place d'une image fallback si aucun lien vers une image n'est fourni, et si la cible du lien ne peut pas être atteinte, on montre le placeholder -->
                    <img src="<?php echo htmlspecialchars($products['product_image_url'] ?: 'images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($products['product_name']); ?>" onerror="this.src='images/placeholder.jpg'">
                  </div>

                  <!-- Détails du produit -->
                  <div class="productCardDetails">

                    <!-- Nom et catégorie du produit -->
                    <div class="productCardDetailsNameAndCat"> 
                      <a class="productCardDetailsName" href="<?php echo htmlspecialchars($products['product_url'] ?? '');?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($products['product_name']);?></a>
                      <!-- Si la catégorie n'a pas été renseignée, c'est-à-dire est NULL, ne pas afficher la zone -->
                      <?php if ($products['category_name']): ?><p class="productCardDetailsCategory"><?php echo htmlspecialchars($products['category_name'] ?? '');?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Magasin et description du produit -->

                    <div class="productCardDetailsShopAndDetails">
                      <p class="productCardDetailsShop"><?php echo htmlspecialchars($products['shop_name'] ?? '');?></p>
                      <p class="productCardDetailsDescription"><?php echo htmlspecialchars($products['product_description'] ?? '');?></p>
                    </div>

                    <!-- Boutons Modifier et Déplacer -->
                    <div class="productCardDetailsModifyAndMove">
                      <!-- La propriété window.location.href permet, en assignant une nouvelle valeur, de rediriger le navigateur vers cette nouvelle URL, comme quand on clique sur un lien. 'addproduct.php?id=...' correspond à l'url vers laquelle on redirige et le ?id= est un paramètre GET qui permet de passer une valeur dans l'URL. Ensuite php echo htmlspecialchars($products['product_id']); insère dynamiquement l'id du produit à modifier dans l'URL  -->
                      <button class="productCardDetailModifyButton" type="button" onclick="window.location.href='addproduct.php?id=<?php echo htmlspecialchars($products['product_id']); ?>'" aria-label="Modifier le produit <?php echo htmlspecialchars($products['product_name']);?>">Modifier</button>
                      <button class="productCardDetailMoveButton" type="button" aria-label="Déplacer le produit <?php echo htmlspecialchars($products['product_name']);?>">Déplacer</button>
                    </div>

                  </div>
                  
                  <!-- Gestion du produit : Données chiffrées et priorités du produit -->
                  <div class="productCardNumbers">

                    <!-- Supprimer le produit -->
                    <div class="productCardNumbersDelete">

                      <!-- Mise en place de la suppression du produit à l'aide d'un formulaire qui renvoie les données en POST vers cette même page pour que PHP reçoive les données et traite la suppression -->
                      <form method="POST" action="wishlist.php">
                        <!-- Champ caché de l'utilisateur qui envoie le product_id du produit concerné au serveur -->
                        <input type="hidden" name="delete_product_id" value="<?php echo htmlspecialchars($products['product_id']);?>">
                        <!-- Name="delete_action" : grâce à delete_action, le lien se fait avec isset($_POST['delete_action']) et permet de détecter que c'est ce bouton qui a déclenché la soumission. Onclick : affiche une boîte de dialogue JS, si l'utilisateur clique sur annuler, renvoie false et empêche la soumission du formulaire, sinon renvoie true -->
                        <button class="productCardNumbersDeleteButton" type="submit" name="delete_action" onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer ce produit de votre liste d\'envies ?')" aria-label="Supprimer le produit <?php echo htmlspecialchars($products['product_name']);?>">Supprimer</button>
                      </form>
                    </div>

                    <!-- Priorité et prix -->
                    <div class="productCardNumbersPriorityAndPrice">

                      <!-- Priorité du produit -->
                      <label for="priority-<?php echo htmlspecialchars($products['product_id']); ?>" class="productCardNumbersPriorityLabel">Priorité</label>
                      <select class="productCardNumbersPriority" id="priority-<?php echo htmlspecialchars($products['product_id']); ?>" name="priority">
                        <option value="" disabled="">-- Choisir la priorité --</option>
                        <option value="1" <?php echo ($products['product_priority'] == 1) ? 'selected' : ''; ?>>Priorité : Très haute</option>
                        <option value="2" <?php echo ($products['product_priority'] == 2) ? 'selected' : ''; ?>>Priorité : Haute</option>
                        <option value="3" <?php echo ($products['product_priority'] == 3) ? 'selected' : ''; ?>>Priorité : Moyenne</option>
                        <option value="4" <?php echo ($products['product_priority'] == 4) ? 'selected' : ''; ?>>Priorité : Faible</option>
                        <option value="5" <?php echo ($products['product_priority'] == 5) ? 'selected' : ''; ?>>Priorité : Très Faible</option>
                      </select>

                      <!-- Prix du produit -->
                      <p class="productCardNumbersPrice" aria-label="Prix du produit <?php echo htmlspecialchars($products['product_name']);?>"><?php echo htmlspecialchars($products['product_price']);?></p>
                    </div>

                    <!-- Quantité et décision d'achat du produit -->
                    <div class="productCardNumbersNumberAndBuy">
                      <input type="number" min="1" class="productCardNumbersNumber" id="number-<?php echo htmlspecialchars($products['product_id']); ?>" value="<?php echo htmlspecialchars($products['product_quantity']); ?>">
                      <label class="productCardNumbersNumberLabel" for="number-<?php echo htmlspecialchars($products['product_id']); ?>">Nombre</label>
                      <!-- <button class="productCardNumbersBuy" type="button">Je l'offre !</button> -->
                    </div>

                  </div>

                  <!-- Icône de déplacement du produit -->
                  <div class="productCardDrag" aria-label="Déplacer le produit">
                    <svg class="dragIcons arrowUp" fill="#1E293B" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"> <g id="SVGRepo_bgCarrier" stroke-width="0"></g> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g> <g id="SVGRepo_iconCarrier"> <path d="M0.256 23.481c0 0.269 0.106 0.544 0.313 0.75 0.412 0.413 1.087 0.413 1.5 0l14.119-14.119 13.913 13.912c0.413 0.413 1.087 0.413 1.5 0s0.413-1.087 0-1.5l-14.663-14.669c-0.413-0.412-1.088-0.412-1.5 0l-14.869 14.869c-0.213 0.212-0.313 0.481-0.313 0.756z"></path> </g></svg>
                        
                    <svg class="dragIcons arrowDown" fill="#1E293B" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" transform="matrix(1, 0, 0, -1, 0, 0)"> <g id="SVGRepo_bgCarrier" stroke-width="0"></g> <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g> <g id="SVGRepo_iconCarrier"> <path d="M0.256 23.481c0 0.269 0.106 0.544 0.313 0.75 0.412 0.413 1.087 0.413 1.5 0l14.119-14.119 13.913 13.912c0.413 0.413 1.087 0.413 1.5 0s0.413-1.087 0-1.5l-14.663-14.669c-0.413-0.412-1.088-0.412-1.5 0l-14.869 14.869c-0.213 0.212-0.313 0.481-0.313 0.756z"></path> </g></svg>
                  </div>

                </div>
              <?php endforeach; ?>
            </div>

         </section>
    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>

  <script src="js/wishlist.js">

  </script>

  </body>
</html>