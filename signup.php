<?php 
var_dump($_POST);
// print_r($_POST);

require_once 'config/database.php';
// Établir la connexion entre la page et le fichier database.php :
// require_once "config/database.php";

// Préparation de la requête :
// $requete = $connection->prepare("REQUETE_SQL");

// Exécution de la requête :
// $requete->execute();

// Récupérer le résultat :
// $resultat = $requete->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userName'], $_POST['firstName'],$_POST['name'], $_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['address'], $_POST['city'], $_POST['postalCode'], $_POST['country'])) {

  $user_name = trim($_POST['userName']);
  $first_name = trim($_POST['firstName']);
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password1 = trim($_POST['password1']);
  $password2 = trim($_POST['password2']);
  $address = trim($_POST['address']);
  $city = trim($_POST['city']);
  $postal_code = trim($_POST['postalCode']);
  $country = trim($_POST['country']);

  echo escape_HTML($user_name);
  echo escape_HTML($first_name);
  echo escape_HTML($name);
  echo escape_HTML($email);
  echo escape_HTML($address);
  echo escape_HTML($city);
  echo escape_HTML($postal_code);
  echo escape_HTML($country);
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
        <!-- Introduction à la page d'Inscription -->
        <section class="contactSection"> <!--TODO modifier noms de classe-->

            <!-- Div à rétablir si nécessaire <div class="contactPageIntro"> -->
                
                <!-- Titre de la page -->
                <div class="pageTitleBox">
                    <h1 class="h1FormPages">Enregistrez-vous pour créer votre liste</h1>
                </div>

                <!-- Paragraphe contact -->
                <div class="pageParagraphBox">
                    <p class="contactP">Prenez quelques instants pour saisir vos informations personnelles afin de créer votre liste d'envies et la partager avec vos proches.</p>
                </div>

            <!-- </div> -->
        </section>
        <!-- Formulaire de contact -->
        <section class="contactFormSection">

            <!-- Début formulaire -->           
            <div class="formCard">
            <form class="form" method="POST" action="http://localhost/wishlog/php/traitement.php">

            <!-- Champs d'identification -->
              <!-- Champ pseudo -->
              <div class="userNameBlock">
                  <label for="userName">Pseudo<span class="required"> *</span></label>
                  <input type="text" id="userName" class="inputFields" name="userName" placeholder="Entrez votre nom d'utilisateur" required>
              </div>
              <!-- Champ prénom & nom-->
              <div class="idBlock">
                <div class="rowId">
                    <label for="firstName">Prénom</label>
                    <input type="text" id="firstName" class="inputFields" name="firstName" placeholder="Entrez votre prénom" required>
                </div>
                <div class="rowId">
                  <label for="name">Nom</label>
                  <input type="text" id="name" class="inputFields nameLabel" name="name" placeholder="Entrez votre nom" required>
                </div>
              </div>

              <!-- Champ e-mail -->
              <div class="emailBlock">
                  <label for="email">E-mail<span class="required"> *</span></label>
                  <input type="email" id="email" class="inputFields" name="email" placeholder="votre.email@example.com" required>
              </div>

              <!-- Champs mot de passe -->
                <div class="rowPass">
                    <label for="password1">Mot de passe<span class="required"> *</span></label>
                    <input type="password" id="password1" class="inputFields" name="password1" placeholder="Entrez votre mot de passe" required>
                </div>
                <div class="rowPass">
                  <label for="password2">Confirmation du mot de passe<span class="required"> *</span></label>
                  <input type="password" id="password2" class="inputFields nameLabel" name="password2" placeholder="Confirmation de votre mot de passe" required>
                </div>


              <!-- Champs adresse -->
                <div class="rowAddress">
                  <label for="address">Adresse</label>
                  <input type="text" id="address" class="inputFields" name="address" required placeholder="Entrez votre adresse">
                </div>
                <div class="cityAndCodeBlock">
                <div class="addressBlock">
                  <label for="city">Ville</label>
                  <input type="text" id="city" class="inputFields" name="city" required placeholder="Entrez votre ville">
                </div>
                <div class="addressBlock">
                <label for="postalCode">Code postal</label>
                <input type="text" id="postalCode" class="inputFields" name="postalCode" placeholder="Entrez votre code postal" required>
                </div>
                <div class="rowCountry">
                  <label for="country">Pays</label>
                  <input type="text" id="country" class="inputFields" name="country" required placeholder="Entrez votre pays">
                </div>
              </div>

            <!-- Bouton d'envoi -->
            <div class="submitFormButton">
            <button class="submitButton" type="submit">Valider</button>
            </div>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>


  </body>
  </html>