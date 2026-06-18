<?php
session_start();
// Inclusion de session_start() par rapport à la variation du header qui se trouve en include

// Établir la connexion entre la page et le fichier database.php :
require_once 'config/database.php';

// Empêcher que l'utilisateur puisse s'inscrire à nouveau s'il est connecté :
if (isset($_SESSION['user_id'])) {
  header ('Location: wishlist.php');
  exit();
}

// 1 - Vérification que le formulaire a bien été soumis en POST, + vérifications que les champs obligatoires sont bien remplis avant de traiter :
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['userName'], $_POST['email'], $_POST['password1'], $_POST['password2'])) {

  // 2-1 On récupère et on stocke dans une variable les valeurs des champs obligatoires depuis le formulaire avec mb_strtolower pour forcer les minuscules sur la saisie utilisateur et on nettoie ces données avec trim():
  $user_name = mb_strtolower(trim($_POST['userName']));
  $email = mb_strtolower(trim($_POST['email']));
  $password1 = trim($_POST['password1']);
  $password2 = trim($_POST['password2']);

  // 2-2 On récupère aussi les valeurs des champs non-obligatoires depuis le formulaire et on les nettoie avec trim() :
  $first_name = trim($_POST['firstName'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $postal_code = trim($_POST['postalCode'] ?? '');
  $country = trim($_POST['country'] ?? '');

  // 3 - Utilisation de $has_errors pour servir d'alerte pour que dès qu'une validation échoue on le passe à true pour empêcher le reste du traitement de s'exécuter : 
  $has_errors = false;

  // 4 - Vérification du format de certains champs et que les deux mots de passe sont identiques :
  // 4-1 Contraintes de longueur pour le nom d'utilisateur avec strlen() :
  // 4-1-1 On applique d'abord mb_strlen() (qui compte les caractères au lieu des octets, au cas où je changerais certaines règles plus tard) au nom d'utilisateur et on récupère la valeur dans une variable pour ne calculer qu'une seule fois ensuite dans le if :
  $user_name_length = mb_strlen($user_name);
  if ($user_name_length < 3 || $user_name_length > 50) {
    $user_name_syntax_length_error_message = "La longueur de votre nom d'utilisateur doit être comprise entre 3 et 50 caractères. Veuillez saisir un nom d'utilisateur valide.";
    $has_errors = true;
  }

  // 4-2 Contraintes de format ASCII pour le nom d'utilisateur avec preg_match + regex (lettres minuscules forcées par mb_strtolower plus haut):
  if (!preg_match('/^(?=.*[a-z])[a-z0-9_]+$/', $user_name)) {
    $user_name_syntax_format_error_message = "Votre nom d'utilisateur doit contenir au moins une lettre et ne peut contenir que des lettres, chiffres et underscores (_).Veuillez saisir un nom d'utilisateur valide.";
    $has_errors = true;
  }


  // 4-3 Limitation de choix de noms d'utilisateurs :
  $reserved_user_names = ['admin', 'administrator', 'moderator', 'root', 'system', 'support', 'staff', 'helpdesk', 'superuser', 'operator', 'null', 'undefined'];

  if (in_array($user_name, $reserved_user_names, true)) {
    $reserved_user_names_error_message = "Ce nom d'utilisateur est réservé. Veuillez choisir un autre nom d'utilisateur.";
    $has_errors = true;
  }

  // TODO : Finir de mettre en place une limitation de choix de nom d'utilisateur en cumulant cette fois in_array ET ensuite en ajoutant la limitation de choix à l'aide de préfixes et d'un foreach et de str_starts_with():
  // $reserved_user_names = ['null', 'undefined'];

  // if (in_array($user_name, $reserved_user_names, true)) {
  //     $user_name_reserved_error_message = "Ce nom d'utilisateur est réservé. Veuillez choisir un autre nom d'utilisateur";
  // }
  //   $reserved_prefixes_user_names = ['admin', '_admin', 'administrator', '_administrator', 'moderator', '_moderator', 'root', '_root', 'system', '_system', 'support', '_support', 'staff', '_staff', 'helpdesk', '_helpdesk','superuser', '_superuser', 'operator', '_operator'];
  // TODO : écrire ici le foreach

  // 4-4 Vérification que l'e-mail saisi est valide avec filter_var() : 
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_syntax_error_message = "L'adresse e-mail saisie est invalide. Veuillez saisir une adresse e-mail valide.";
    $has_errors = true;
  }

  // 4-5 Contrainte de longueur pour le mot de passe :
  $password_length = mb_strlen($password1);
  if ($password_length < 8) {
    $password_length_error_message = "Votre mot de passe doit comporter au moins 8 caractères (lettres, chiffres, caractères spéciaux). Veuillez saisir un mot de passe valide.";
    $has_errors = true;
  }

  // 4-6 Contrainte de format de mot de passe (1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial) :
  if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9])/', $password1)) {
    $password_format_error_message = "Votre mot de passe doit comporter au moins une minuscule, une majuscule, un chiffre et un caractère spécial. Veuillez saisir un mot de passe valide.";
    $has_errors = true;
  }

  // 4-7 Vérification que le mot de passe et la confirmation de mot de passe sont bien identiques :
  if ($password1 !== $password2) {
    $signup_password_error_message = "Erreur : les mots de passe ne sont pas identiques. Veuillez modifier votre saisie avant de vous enregistrer.";
    $has_errors = true;
  }

  // 5 - Une fois toutes les validations effectuées ci-dessus, on vérifie $has_errors. Si une erreur a été détectée : on n'entre pas dans le bloc et le traitement s'arrête. Si aucune erreur n'a été détectée on continue vers les vérifications en base de données et l'insertion :
  if (!$has_errors) {

    // 6 Vérification que le nom d'utilisateur n'est pas déjà pris dans la base de données :

    // 6-1 Requête pour comparer le nom d'utilisateur afin de vérifier qu'il n'est pas déjà pris dans la base de données :
    $create_username_attempt = $pdo->prepare(
      "SELECT user_name
      FROM wl_user
      WHERE user_name = :userName"
    );

    // 6-2 Exécution de la requête de vérification du nom d'utilisateur: 
    $create_username_attempt->execute(
      [':userName' => $user_name]
    );

    // 6-3 Récupérer le résultat de $create_username_attempt :
    $create_username_result = $create_username_attempt->fetch();

    // 6-4 Si fetch renvoie des données concernant le nom d'utilisateur, message d'erreur :
    if ($create_username_result !== false) {
      $signup_username_error_message = "Erreur : le nom d'utilisateur que vous avez choisi est indisponible. Veuillez choisir un autre nom d'utilisateur.";
    } else {
      // 6-4-1 Si le pseudo est libre, vérifier que l'e-mail n'est pas déjà présent dans la base de données :
      $email_registration_attempt = $pdo->prepare(
        "SELECT user_email
          FROM wl_user
          WHERE user_email = :email"
      );
      // 6-4-2 Exécution de la requête de vérification de l'email :
      $email_registration_attempt->execute(
        [':email' => $email]
      );

      // 6-4-3 Récupérer le résultat de $email_registration_attempt :
      $email_registration_result = $email_registration_attempt->fetch();

      // 6-4-4 Si fetch renvoie des données concernant l'e-mail, message d'erreur :
      if ($email_registration_result !== false) {
        $signup_email_error_message = "Erreur : un compte a déjà été créé avec cette adresse e-mail. Veuillez utiliser la procédure de récupération de mot de passe ou vous inscrire avec une autre adresse e-mail.";
      } else {
        // 7 -  Hashage du mot de passe :
        $hash = password_hash($password1, PASSWORD_DEFAULT);

        // 8 - Assignation de la valeur 2 à la variable role_id :
        $role_id = 2;

        // 9-1 Insertion des données dans la base de données avec try/catch au cas où il y ait une erreur pour accéder à la base :
        try {
          // 9-2 Préparation de la requête pour l'insertion des données de l'utilisateur dans la table wl_user :
          $insert_new_user = $pdo->prepare(
            "INSERT INTO `wl_user` (user_name, user_email, user_password_hash, role_id, user_firstname, user_lastname, user_address, user_postalcode, user_city, user_country)
              VALUES
              (:userName, :email, :password1, :role_id, :firstName, :lastName, :userAddress, :city, :postalCode, :country)"
          );

          // 9-3 Exécution de la requête :
          $insert_new_user->execute(
            [':userName' => $user_name, ':email' => $email, ':password1' => $hash, ':role_id' => $role_id, ':firstName' => $first_name, ':lastName' => $name, ':userAddress' => $address, ':city' => $city, ':postalCode' => $postal_code, ':country' => $country]
          );

          // 9-4 Mise en place d'un message flash : c'est un message de succès qui sera stocké temporairement dans la session, le temps de l'afficher sur la page suivante.
          $_SESSION['signup_success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";

          // 10 - Redirection de l'utilisateur vers la page login.php :
          header('Location: login.php');

          // 11 - Arrêt de l'exécution du reste du code PHP pour éviter de causer des comportements inattendus si PHP continue d'exécuter le code après la redirection :
          exit();

          // 12-1 Gestion des erreurs avec le type d'erreur PDOException (erreurs liées à la base de données) & $error pour récupérer le message d'erreur technique :
        } catch (PDOException $error) {
          // 12-2 Gestion des erreurs éventuelles lors de la soumission de l'ajout : Utilisation d'error_log() qui est une fonction native PHP qui écrit un message d'erreur dans le fichier de log du serveur. Cela permet d'enregistrer les erreurs techniques sans les afficher à l'utilisateur qui ne voit que le message générique. L'objectif est de ne révéler aucune information sensible sur la base de données. getMessage() est une méthode de la classe Exception qui retourne le message textuel décrivant l'erreur.
          error_log($error->getMessage());

          // 12-3 Message à destination de l'utilisateur pour l'informer de l'échec de l'insertion :
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
<?php require_once 'includes/head.php'; ?>

<body>
  <!--Début du code du contenu de la page-->

  <!-- Utilisation de require_once pour inclure le header (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
  <?php require_once 'includes/header.php'; ?>


  <main>
    <!-- Introduction à la page d'Inscription -->
    <section class="signupSection"> 

      <!-- Titre de la page -->
      <div class="pageTitleBox">
        <h1 class="h1FormPages">Enregistrez-vous pour créer votre liste</h1>
      </div>

      <!-- Paragraphe inscription -->
      <div class="pageParagraphBox">
        <p class="signupP">Prenez quelques instants pour saisir vos informations personnelles afin de créer votre liste d'envies et la partager avec vos proches.</p>
      </div>

      <!-- </div> -->
    </section>
    <!-- Formulaire d'inscription -->
    <section class="signupFormSection">

      <!-- Début formulaire -->
      <div class="formCard">
        <form class="form" method="POST" action="signup.php">

          <!-- Champs d'identification -->
          <!-- Champ pseudo -->
          <div class="emailBlock">
            <label for="userName">Nom d'utilisateur<span class="required"> *</span></label>
            <input type="text" id="userName" class="inputFields" name="userName" placeholder="Entrez votre nom d'utilisateur" required>
          </div>

          <!-- Affichage du message d'erreur en cas de nom d'utilisateur trop court -->
          <?php if (isset($user_name_syntax_length_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($user_name_syntax_length_error_message); ?></p>
          <?php endif; ?>

          <!-- Affichage du message d'erreur en cas de nom d'utilisateur ne respectant pas le format imposé : -->
          <?php if (isset($user_name_syntax_format_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($user_name_syntax_format_error_message); ?></p>
          <?php endif; ?>

          <!-- Affichage du message d'erreur en cas de nom d'utilisateur réservé/interdit : -->
          <?php if (isset($reserved_user_names_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($reserved_user_names_error_message); ?></p>
          <?php endif; ?>

          <!-- Affichage du message d'erreur en cas de nom d'utilisateur déjà pris -->
          <?php if (isset($signup_username_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($signup_username_error_message); ?></p>
          <?php endif; ?>

          <!-- Champ prénom & nom-->
          <!-- <div class="idBlock"> -->
            <div class="signupIdBlock">
              <label for="firstName">Prénom</label>
              <input type="text" id="firstName" class="inputFields" name="firstName" placeholder="Entrez votre prénom">
            </div>
            <div class="signupIdBlock">
              <label for="name">Nom</label>
              <input type="text" id="name" class="inputFields nameLabel" name="name" placeholder="Entrez votre nom">
            </div>
          <!-- </div> -->

          <!-- Champ e-mail -->
          <div class="emailBlock">
            <label for="email">E-mail<span class="required"> *</span></label>
            <input type="email" id="email" class="inputFields" name="email" placeholder="votre.email@example.com" required>
          </div>

          <!-- Affichage du message d'erreur en cas d'erreur d'email de type invalide : -->
          <?php if (isset($email_syntax_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($email_syntax_error_message); ?></p>
          <?php endif; ?>

          <!-- Affichage du message d'erreur en cas de nom d'e-mail déjà utilisé -->
          <?php if (isset($signup_email_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($signup_email_error_message); ?></p>
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

          <!-- Affichage du message d'erreur en cas de mot de passe trop court : -->
          <?php if (isset($password_length_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($password_length_error_message); ?></p>
          <?php endif; ?>

          <!-- Affichage du message d'erreur en cas de mot de passe dont le format est invalide : -->
          <?php if (isset($password_format_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($password_format_error_message); ?></p>
          <?php endif; ?>

          <!-- Affichage du message d'erreur en cas de différence entre les deux mots de passe -->
          <?php if (isset($signup_password_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($signup_password_error_message); ?></p>
          <?php endif; ?>


          <!-- Champs adresse -->
          <div class="rowAddress">
            <label for="address">Adresse</label>
            <input type="text" id="address" class="inputFields" name="address" placeholder="Entrez votre adresse">
          </div>
              <div class="addressBlock">
                <label for="city">Ville</label>
                <input type="text" id="city" class="inputFields" name="city" placeholder="Entrez votre ville">
              </div>
              <div class="addressBlock">
                <label for="postalCode">Code postal</label>
                <input type="text" id="postalCode" class="inputFields" name="postalCode" placeholder="Entrez votre code postal">
              </div>

            <div class="rowCountry">
              <label for="country">Pays</label>
              <input type="text" id="country" class="inputFields" name="country" placeholder="Entrez votre pays">
            </div>


          <!-- Bouton d'envoi -->
          <div class="submitFormButton">
            <button class="submitButton" type="submit">Valider</button>
          </div>
          <?php if (isset($signup_final_error_message)) : ?>
            <p class="signupError"><?php echo htmlspecialchars($signup_final_error_message); ?></p>
          <?php endif; ?>

        </form>

      </div>

    </section>

  </main>

  <!-- Utilisation de require_once pour inclure le footer (de façon centralisée et modifiable) une seule et unique fois : permet d'éviter des bugs, les doublons -->
  <?php require_once 'includes/footer.php'; ?>


</body>

</html>