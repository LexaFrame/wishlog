<?php
session_start();
// ^Vérifier s'il y a une session existante pour adapter le header en conséquence, à écrire en premier avant tout affichage HTML
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
        <!-- Introduction à la page Contact -->
        <section class="contactSection">

            <!-- Div à rétablir si nécessaire <div class="contactPageIntro"> -->
                
                <!-- Titre de la page -->
                <div class="pageTitleBox">
                    <h1 class="h1Contact">Contact</h1>
                </div>

                <!-- Paragraphe contact -->
                <div class="pageParagraphBox">
                    <p class="contactP">Créer et partager ses envies doit rester simple et agréable. Pour toute question, souci technique ou idée d'amélioration, écrivez-nous.</p>
                </div>

            <!-- </div> -->
        </section>
        <!-- Formulaire de contact -->
        <section class="contactFormSection">

            <!-- Début formulaire -->
            <div class="formCard">
            <form class="form"method="POST" action="#"> <!-- TODO saisir lien dans action ="" -->

              <!-- Champs d'identification -->
              <!-- <div class="idBlock"> -->
                <div class="contactIdBlock">
                    <label for="firstName">Prénom<span class="required"> *</span></label>
                    <input type="text" id="firstName" class="inputFields" name="firstName" placeholder="Entrez votre prénom" required>
                </div>
                <div class="contactIdBlock">
                  <label for="name">Nom<span class="required"> *</span></label>
                  <input type="text" id="name" class="inputFields nameLabel" name="name" placeholder="Entrez votre nom" required>
                </div>
              <!-- </div> -->

              <!-- Champ e-mail -->
              <div class="contactEmailBlock">
                  <label for="email">E-mail<span class="required"> *</span></label>
                  <input type="email" id="email" class="inputFields" name="email" placeholder="votre.email@example.com" required>
              </div>

              <!-- Champ n° téléphone -->
              <div class="contactPhoneBlock">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" class="inputFields" name="phone" placeholder="06 12 34 56 78">
              </div>

              <!-- Champs adresse -->

                <div class="contactAddress">
                  <label for="address">Adresse<span class="required"> *</span></label>
                  <input type="text" id="address" class="inputFields" name="address" required placeholder="Entrez votre adresse">
                </div>
                <!-- <div class="cityAndCodeBlock"> -->
                <div class="contactAddress">
                  <label for="city">Ville<span class="required"> *</span></label>
                  <input type="text" id="city" class="inputFields" name="city" required placeholder="Entrez votre ville">
                </div>
                <div class="contactAddress">
                <label for="postalCode">Code postal<span class="required"> *</span></label>
                <input type="text" id="postalCode" class="inputFields" name="postalCode" placeholder="Entrez votre code postal" required>
                </div>
                <!-- </div> -->

              <!-- Sélection sujet -->              
              <div class="contactSubjectBlock">
                <label for="subject">Sujet<span class="required"> *</span></label>
                <select id="subject" name="subject" required>
                  <option value="selection">-- Sélectionnez un sujet --</option>
                  <option value="technicalIssue">Problème technique</option>
                  <option value="infoDemand">Demande d'informations</option>
                  <option value="hiring">Recrutement</option>
                </select>  
              </div>

              <!-- Zone de saisie du message -->
              <div class="contactMessageBlock">
                <label for="message" class="messageLabel">Message<span class="required"> *</span></label>
                <textarea id="message" name="message" class="inputFields" placeholder="Entrez votre message..." rows="5" cols="30" required> Entrez votre message...
                </textarea>
              </div>

            <!-- Bouton d'envoi -->
            <div class="submitFormButton">
            <button class="submitButton" type="submit">Envoyer</button>
            </div>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>

  </body>
</html>