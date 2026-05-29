<?php 
session_start(); // 1- Vérifier que la session n'est pas déjà ouverte

// 2 - Établir la connexion entre la page et le fichier database.php :
require_once 'config/database.php';

// var_dump($_POST);
// print_r($_POST);

// Préparation de la requête :
// $requete = $pdo->prepare("REQUETE_SQL");

// Exécution de la requête :
// $requete->execute();

// Récupérer le résultat :
// $resultat = $requete->fetchAll();

// 3 - Vérifier que la requête est bien POST et que les champs e-mail et mot de passe sont bien remplis par l'utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password1'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password1']);
    // 3-2 Requête pour récupérer les informations de l'utilisateur pour vérifier qu'elles correspondent à ce qui figure déjà dans la base de données :
    $login_attempt = $pdo->prepare(
        "SELECT user_id, user_email, user_password_hash
        FROM wl_user
        WHERE user_email = :email"
    );

    // 3-3 Exécution de la requête de vérification :
    $login_attempt->execute(
        [':email'=>$email]
    );

    // 3-4 Récupérer le résultat de $login_attempt :
    $login_result = $login_attempt->fetch();

    // 
    if($login_result === false) {
        $login_error_message = "L'e-mail ou le mot de passe est incorrect";
    } else {
        if(!password_verify($password, $login_result['user_password_hash'])) {
            $login_error_message = "L'e-mail ou le mot de passe est incorrect";
        } else {
            $_SESSION['user_id'] = $login_result['user_id'];
            header('Location: index.php');
            exit();
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
            <form class="form" method="POST" action="login.php">

            <!-- Champs d'identification -->
              <!-- Champ pseudo
              <div class="userNameBlock">
                  <label for="userName">Pseudo<span class="required"> *</span></label>
                  <input type="text" id="userName" class="inputFields" name="userName" placeholder="Entrez votre nom d'utilisateur" required>
              </div> -->
              
              <!-- Champ email -->
              <div class="emailBlock">
                  <label for="email">E-mail :<span class="required"> *</span></label>
                  <input type="email" id="email" class="inputFields" name="email" placeholder="Entrez votre adresse e-mail" required>
              </div>


              <!-- Champs mot de passe -->
                <div class="rowPass">
                    <label for="password1">Mot de passe<span class="required"> *</span></label>
                    <input type="password" id="password1" class="inputFields" name="password1" placeholder="Entrez votre mot de passe" required>
                </div>

                <div class="pageParagraphLoginForgotBox">
                    <p class="loginForgot">Identifiant ou mot de passe oublié ? <a href="">cliquez-ici</a>.</p>
                </div>

            <!-- Affichage du message d'erreur en cas de mauvaise adresse e-mail ou mauvais mot de passe -->
            <?php if (isset($login_error_message)) : ?>
                <p class="loginError"><?php echo htmlspecialchars($login_error_message); ?></p>
            <?php endif; ?>

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