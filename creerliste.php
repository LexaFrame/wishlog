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
            <form class="form" method="POST" action=""> <!-- TODO saisir lien dans action ="" -->

            <!-- Champs de détail de la liste d'envies -->
                <!-- Champ titre -->
              <div class="titleCreateWishlistBlock">
                  <label for="titleCreateWishlist">Titre<span class="required"> *</span></label>
                  <input type="text" id="titleCreateWishlist" class="inputFields" name="titleCreateWishlist" placeholder="" required>
              </div>

                <!-- Champ date -->
              <div class="dateCreateWishlistBlock">
                  <label for="dateCreateWishlist">Date de l'évènement</label>
                  <input type="text" id="dateCreateWishlist" class="inputFields" name="dateCreateWishlist" placeholder="" required>
              </div>

                <!-- Sélection type d'évènement -->                          
                <div class="typeOfWishlistBlock">
                    <label for="typeOfWishlist">Type d'évènement<span class ="required"> *</span></label>
                    <select id="typeOfWishlist" name="typeOfWishlist" required>
                        <option value="selection">-- Sélectionnez un type de liste --</option>
                        <option value="birthday">Anniversaire</option>
                        <option value="gift">Cadeaux</option>
                        <option value="holidays">Fêtes</option>
                        <option value="wedding">Mariage</option>
                        <option value="baby">Naissance</option>                                                                        
                    </select>  
                </div>

                <!-- Zone de saisie de la description de la liste -->
              <div class="descriptionCreateWishlistBlock">
                <label for="descriptionCreateWishlist" class="descriptionCreateWishlistLabel">Description de la liste d'envies</label><br>
                <textarea id="descriptionCreateWishlist" name="descriptionCreateWishlist" class="inputFields" placeholder="Entrez votre message..." rows="5" cols="30" required>
                </textarea>
              </div>

                <!-- Case à cocher "ne pas me gâcher la surprise -->
              <div class="keepSurpriseBlock">
                  <label for="keepSurprise">Ne pas me gâcher la surprise</label>
                  <input type="checkbox" id="keepSurprise" class="inputFields" name="keepSurprise" placeholder="" required>
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