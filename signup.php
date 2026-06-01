<?php 
session_start();
// Inclusion de session_start() par rapport à la variation du header qui se trouve en include

// Établir la connexion entre la page et le fichier database.php :
require_once 'config/database.php';

// 1 - Vérification que le formulaire a bien été soumis en POST, + vérifications que les champs obligatoires sont bien remplis avant de traiter :
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['userName'], $_POST['email'], $_POST['password1'], $_POST['password2'])) {

  // 2-1 On récupère et on stocke dans une variable les valeurs des champs obligatoires depuis le formulaire et on les nettoie avec trim() :
  $user_name = trim($_POST['userName']);
  $email = trim($_POST['email']);
  $password1 = trim($_POST['password1']);
  $password2 = trim($_POST['password2']);

  // 2-2 On récupère aussi les valeurs des champs non obligatoires depuis le formulaire et on les nettoie avec trim() :
  $first_name = trim($_POST['firstName'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $postal_code = trim($_POST['postalCode'] ?? '' );
  $country = trim($_POST['country'] ?? '');

  // 3 - Vérification que les deux mots de passe sont identiques :
  if ($password1 !== $password2) {
    $signup_password_error_message = "Erreur : les mots de passe ne sont pas identiques. Veuillez modifier votre saisie avant de vous enregistrer.";
  } else {
    // 4 Vérification que le nom d'utilisateur n'est pas déjà pris dans la base de données :

      // 4-1 Requête pour comparer le nom d'utilisateur afin de vérifier qu'il n'est pas déjà pris dans la base de données :
      $create_username_attempt = $pdo->prepare(
        "SELECT user_name
        FROM wl_user
        WHERE user_name = :userName"
      );

      // 4-2 Exécution de la requête de vérification du nom d'utilisateur: 
      $create_username_attempt->execute(
        [':userName'=>$user_name]
      );

      // 4-3 Récupérer le résultat de $create_username_attempt :
      $create_username_result = $create_username_attempt->fetch();

      // 4-4 Si fetch renvoie des données concernant le nom d'utilisateur, message d'erreur :
      if ($create_username_result !== false) {
        $signup_username_error_message = "Erreur : le nom d'utilisateur que vous avez choisi est indisponible. Veuillez choisir un autre nom d'utilisateur.";
      } else {
        // 5-1 Si le pseudo est libre, vérifier que l'e-mail n'est pas déjà présent dans la base de données :
        $email_registration_attempt = $pdo->prepare(
          "SELECT user_email
          FROM wl_user
          WHERE user_email = :email"
        );
        // 5-2 Exécution de la requête de vérification de l'email :
          $email_registration_attempt->execute(
            [':email'=>$email]
          );

        // 5-3 Récupérer le résultat de $email_registration_attempt :
        $email_registration_result = $email_registration_attempt->fetch();

        // 5-4 Si fetch renvoie des données concernant l'e-mail, message d'erreur :
        if ($email_registration_result !== false) {
          $signup_email_error_message = "Erreur : un compte a déjà été créé avec cette adresse e-mail. Veuillez utiliser la procédure de récupération de mot de passe ou vous inscrire avec une autre adresse e-mail.";
        } else {
          // 6 -  Hashage du mot de passe :
          $hash = password_hash($password1, PASSWORD_DEFAULT);

          // 7 - Assignation de la valeur 2 à la variable role_id :
          $role_id = 2;

          // 8-1 Insertion des données dans la base de données avec try/catch au cas où il y a une erreur pour accéder à la base :
          try {
            // 8-2 Préparation de la requête pour l'insertion des données de l'utilisateur dans la table wl_user :
            $insert_new_user = $pdo->prepare(
              "INSERT INTO `wl_user` (user_name, user_email, user_password_hash, role_id, user_firstname, user_lastname, user_address, user_postalcode, user_city, user_country)
              VALUES
              (:userName, :email, :password1, :role_id, :firstName, :lastName, :userAddress, :city, :postalCode, :country)"
            );

            // 8-3 Exécution de la requête :
            $insert_new_user->execute(
              [':userName'=>$user_name, ':email'=>$email, ':password1'=>$hash, ':role_id'=>$role_id, ':firstName'=>$first_name, ':lastName'=>$name, ':userAddress'=>$address, ':city'=>$city, ':postalCode'=>$postal_code, ':country'=>$country]
            );

            // 8-4 Mise en place d'un message flash : c'est un message de succès qui sera stocké temporairement dans la session, le temps de l'afficher sur la page suivante.
            $_SESSION['signup_success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";

            // 9 - Redirection de l'utilisateur vers la page login.php :
            header('Location: login.php');

            // 10 - Arrêt de l'exécution du reste du code PHP pour éviter de causer des comportements inattendus si PHP continue d'exécuter le code après la redirection :
            exit();

            // 11-1 Gestion des erreurs avec le type d'erreur PDOException (erreurs liées à la base de données) & $error pour récupérer le message d'erreur technique :
          } catch (PDOException $error) {
            // 11-2 Gestion des erreurs éventuelles lors de la soumission de l'ajout : Utilisation d'error_log() qui est une fonction native PHP qui écrit un message d'erreur dans le fichier de log du serveur. Cela permet d'enregistrer les erreurs techniques sans les afficher à l'utilisateur qui ne voit que le message générique. L'objectif est de ne révéler aucune information sensible sur la base de données. getMessage() est une méthode de la classe Exception qui retourne le message textuel décrivant l'erreur.
            error_log($error->getMessage());

            // 11-3 Message à destination de l'utilisateur pour l'informer de l'échec de l'insertion :
            $signup_final_error_message = "Une erreur est survenue. Le compte utilisateur n'a pas pu être créé.";
          }
        }
      }

    
  }
}

// TODO : mettre en place une confirmation d'inscription par mail.
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
            <form class="form" method="POST" action="signup.php">

            <!-- Champs d'identification -->
              <!-- Champ pseudo -->
              <div class="userNameBlock">
                  <label for="userName">Nom d'utilisateur<span class="required"> *</span></label>
                  <input type="text" id="userName" class="inputFields" name="userName" placeholder="Entrez votre nom d'utilisateur" required>
              </div>

              <!-- Affichage du message d'erreur en cas de nom d'utilisateur déjà pris -->
              <?php if(isset($signup_username_error_message)) : ?>
                <p class="signupError"><?php echo htmlspecialchars($signup_username_error_message);?></p>
              <?php endif; ?>

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

              <!-- Affichage du message d'erreur en cas de nom d'e-mail déjà utilisé -->
              <?php if(isset($signup_email_error_message)) : ?>
                <p class="signupError"><?php echo htmlspecialchars($signup_email_error_message);?></p>
              <?php endif; ?>

              <!-- Champs mot de passe -->
                <div class="rowPass">
                    <label for="password1">Mot de passe<span class="required"> *</span></label>
                    <input type="password" id="password1" class="inputFields" name="password1" placeholder="Entrez votre mot de passe" required>
                </div>
                <div class="rowPass">
                  <label for="password2">Confirmation du mot de passe<span class="required"> *</span></label>
                  <input type="password" id="password2" class="inputFields nameLabel" name="password2" placeholder="Confirmation de votre mot de passe" required>
                </div>
                
                <!-- Affichage du message d'erreur en cas de différence entre les deux mots de passe -->
                <?php if(isset($signup_password_error_message)) : ?>
                  <p class="signupError"><?php echo htmlspecialchars($signup_error_message);?></p>
                <?php endif; ?>


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
            <?php if(isset($signup_final_error_message)) : ?>
              <p class="signupError"><?php echo htmlspecialchars($signup_final_error_message);?></p>
            <?php endif;?>

             </form>

            </div>
            
        </section>

    </main>

    <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
    <?php require_once 'includes/footer.php';?>


  </body>
  </html>