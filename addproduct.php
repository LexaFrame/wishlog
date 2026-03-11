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
                <!-- TODO décommenter et récupérer dans le CSS les règles correspondantes qui ont disparu <div class="pageTitleBox"> --> 
                    <h1 class="h1AddProduct">Ajoutez un produit</h1> <!-- TODO changer la classe -->
                <!-- </div> -->

                <!-- Paragraphe ajout d'articles -->
                <div class="pageParagraphBox">
                    <p class="addProductP">Saisissez les informations du produit que vous souhaitez ajouter à votre liste.</p>
                </div>

            <!-- </div> -->

        </section>
        <!-- Formulaire d'ajout d'articles' -->
        <section class="addProductFormSection">

            <!-- TODO Début formulaire -> à adapter-->           
            <div class="formCard">
            <form class="form" method="POST" action=""> <!-- TODO saisir lien dans action ="" -->

            <!-- Champs de détail des articles -->
                <!-- Champ titre -->
              <div class="titleAddProductBlock">
                  <label for="titleAddProduct">Titre<span class="required"> *</span></label>
                  <input type="text" id="titleAddProduct" class="inputFields" name="titleAddProduct" placeholder="" required>
              </div>

                <!-- Champ nom du magasin -->
              <div class="shopAddProductBlock">
                  <label for="shopAddProduct">Boutique</label>
                  <input type="text" id="shopAddProduct" class="inputFields" name="shopAddProduct" placeholder="" required>
              </div>

                <!-- Champ prix -->
              <div class="priceAddProductBlock">
                  <label for="priceAddProduct">Prix en €<span class="required"> *</span></label>
                  <input type="text" id="priceAddProduct" class="inputFields" name="priceAddProduct" placeholder="" required>
              </div>

                <!-- Champ quantité -->
              <div class="quantityAddProductBlock">
                  <label for="quantityAddProduct">Quantité</label>
                  <input type="number" id="quantityAddProduct" class="inputFields" name="quantityAddProduct" min="1" step="1" value="1" required>
              </div>                

                <!-- Sélection catégorie -->              
                <div class="categoryAddProductBlock">
                    <label for="categoryAddProduct">Catégorie</label>
                    <select id="categoryAddProduct" name="categoryAddProduct" required>
                        <option value="selection">-- Sélectionnez une catégorie --</option>
                        <option value="newCategory">Nouvelle catégorie</option>
                    </select>  
                </div>

                <!-- Sélection priorité -->              
                <div class="priorityAddProductBlock">
                    <label for="priorityAddProduct">Priorité</label>
                    <select id="priorityAddProduct" name="priorityAddProduct" required>
                        <option value="selection">-- Sélectionnez un niveau de priorité --</option>
                        <option value="newCategory">Priorité : Très haute</option>
                        <option value="newCategory">Priorité : Haute</option>
                        <option value="newCategory">Priorité : Moyenne</option>
                        <option value="newCategory">Priorité : Basse</option>
                        <option value="newCategory">Priorité : Très basse</option>                                                                        
                    </select>  
                </div>

                <!-- Zone de saisie de la description du produit -->
              <div class="descriptionAddProductBlock">
                <label for="descriptionAddProduct" class="descriptionAddProductLabel">Description du produit</label>
                <textarea id="descriptionAddProduct" name="descriptionAddProduct" class="inputFields" placeholder="Entrez votre message..." rows="5" cols="30" required>
                </textarea>
              </div>

                <!-- Champ pour charger une image du produit-->
              <div class="imageAddProductBlock">
                  <label for="imageAddProduct">Photo du produit</label>
                  <input type="file" id="imageAddProduct" class="inputFields" name="imageAddProduct" accept="image/*">
              </div>

                <!-- Champ lien vers le produit-->
              <div class="linkAddProductBlock">
                  <label for="linkAddProduct">Lien</label>
                  <input type="text" id="linkAddProduct" class="inputFields" name="linkAddProduct" placeholder="" required>
              </div>

            <!-- Bouton d'envoi -->
            <div class="submitFormButton">
            <button class="submitButton" type="submit">Ajouter</button>
            </div>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>


  </body>
  </html>