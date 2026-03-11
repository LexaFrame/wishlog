<?php 
var_dump($_POST);
// print_r($_POST);

// Établir la connexion entre la page et le fichier database.php :
// require_once "config/database.php";

// Préparation de la requête :
// $requete = $connection->prepare("REQUETE_SQL");

// Exécution de la requête :
// $requete->execute();

// Récupérer le résultat :
// $resultat = $requete->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userName'], $_POST['email'], $_POST['password1'])) {

  $user_name = trim($_POST['userName']);
  $email = trim($_POST['email']);
  $password1 = trim($_POST['password1']);

  echo escape_HTML($user_name);
  echo escape_HTML($email);
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
                    <h1 class="h1FormPages">Connectez-vous</h1>
                </div>

                <!-- Paragraphe contact -->
                <div class="pageParagraphBox">
                    <p class="contactP">Saisissez vos informations d'identification ou <a href="signup.php">créez votre compte</a> pour créer votre liste d'envies et la partager avec vos proches.</p>
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

              <!-- Champs mot de passe -->
                <div class="rowPass">
                    <label for="password1">Mot de passe<span class="required"> *</span></label>
                    <input type="password" id="password1" class="inputFields" name="password1" placeholder="Entrez votre mot de passe" required>
                </div>

                <div class="pageParagraphLoginForgotBox">
                    <p class="loginForgot">Identifiant ou mot de passe oublié ? <a href="">cliquez-ici</a>.</p>
                </div>

            <!-- Bouton d'envoi -->
            <div class="submitFormButton">
            <button class="submitButton" type="submit">Se connecter</button>
            </div>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>


  </body>
  </html>