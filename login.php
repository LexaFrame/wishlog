<?php 
session_start(); // 1- Vérifier que la session n'est pas déjà ouverte

// 2 - Établir la connexion entre la page et le fichier database.php :
require_once 'config/database.php';

// 3 - Empêcher l'utilisateur de pouvoir se connecter à login.php s'il est déjà connecté :
if (isset($_SESSION['user_id'])) {
    header('Location:wishlist.php');
    exit();
}

// 3 - Connexion de l'utilisateur s'il figure bien dans la base de données (vérification email et mot de passe) et lancement de la session : 

    // 3-1 - Vérifier que la requête est bien POST et que les champs e-mail et mot de passe sont bien remplis par l'utilisateur, si c'est le cas
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password1'])) {

        //3-2 Nettoyer et récupérer la saisie utilisateur dans des variables :
        $email = trim($_POST['email']);
        $password = trim($_POST['password1']);

        // 3-3 Requête pour comparer les informations de l'utilisateur afin de vérifier qu'elles correspondent à ce qui figure déjà dans la base de données :
        $login_attempt = $pdo->prepare(
            "SELECT user_id, user_email, user_password_hash
            FROM wl_user
            WHERE user_email = :email"
        );

        // 3-4 Exécution de la requête de vérification :
        $login_attempt->execute(
            [':email'=>$email]
        );

        // 3-5 Récupérer le résultat de $login_attempt :
        $login_result = $login_attempt->fetch();

        // 3-6 Indiquer à l'utilisateur si sa saisie comporte une erreur :
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
        <section class="loginIntroSection">
                
                <!-- Titre de la page -->
                <div class="pageTitleBox">
                    <h1 class="h1FormPages">Connectez-vous</h1>
                </div>

                <!-- Mise en place du message flash créé dans signup.php suite à la réussite de la création d'un compte utilisateur : -->
                <?php
                if (isset($_SESSION['signup_success'])) : ?>
                    <p class="signupSuccess">
                    <?php echo htmlspecialchars($_SESSION['signup_success']);
                    // Utilisation de unset() qui supprime la clé 'signup_success de $_SESSION après l'avoir affichée pour ne pas que le message réapparaisse à chaque fois que l'utilisateur actualise la page :
                    unset($_SESSION['signup_success']);
                    ?>
                    </p>
                <?php endif; ?>

                <!-- Paragraphe connexion -->
                <div class="pageParagraphBox">
                    <p class="loginP">Saisissez vos informations d'identification ou <a href="signup.php" class="loginLinks">créez votre compte</a> pour créer votre liste d'envies et la partager avec vos proches.</p>
                </div>

            <!-- </div> -->
        </section>
        <!-- Formulaire de connexion -->
        <section class="loginFormSection">

            <!-- Début formulaire -->           
            <div class="loginFormCard">
            <form class="form" method="POST" action="login.php">

            <!-- Champs d'identification -->
              <!-- Champ pseudo
              <div class="userNameBlock">
                  <label for="userName">Pseudo<span class="required"> *</span></label>
                  <input type="text" id="userName" class="inputFields" name="userName" placeholder="Entrez votre nom d'utilisateur" required>
              </div> -->
              
              <!-- Champ email -->
              <div class="loginEmailBlock">
                  <label for="email">E-mail :<span class="required"> *</span></label>
                  <input type="email" id="email" class="inputFields" name="email" placeholder="Entrez votre adresse e-mail" required>
              </div>


              <!-- Champs mot de passe -->
                <div class="loginRowPass">
                    <label for="password1">Mot de passe<span class="required"> *</span></label>
                    <input type="password" id="password1" class="inputFields" name="password1" placeholder="Entrez votre mot de passe" required>
                </div>

                <div class="pageParagraphLoginForgotBox">
                    <p class="loginForgot">Identifiant ou mot de passe oublié ? <a href="#" class="loginLinks">cliquez ici</a>.</p>
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