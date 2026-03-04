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
        <!-- Titre de la page -->
         <div class="pageTitleBox">
            <h1 class="h1Wishlist">Liste d'envies de [Prénom Nom]</h1>
        </div> 
        
        <!-- Bannière à thème de la liste -->
         <img src="images/achristmasgift.jpg" class="wishlistBanner" alt="Photo d'un paquet cadeau" />

         <!-- Description de la liste -->
         <section class="wishlistDescriptionSection">
            <p class="wishlistDescriptionParagraph">Bienvenue sur cette liste d'envies qui me permet de garder une trace d'objets qui me plaisent pour un futur achat et qui peut aussi aider les proches en mal d'inspiration.</p>
         </section>

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
            
            <div class="wishlistProductsSectionCardBox">
            </div>

         </section>
    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>

  <script src="js/wishlist.js">

  </script>

  </body>
</html>