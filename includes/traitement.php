<?php

// Fonction pour échapper les saisies de HTML, les guillemets simples ou doubles et pour prendre en compte les caractères accentués et spéciaux) :
function escape_HTML($string) {
    return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
}

// Fonction pour vérifier que le mot de passe saisi et sa confirmation sont identiques :
function password_check($password1, $password2) {
    return $password1 === $password2;
}

// Assignation dans les variables :

$user_name = $_POST['userName'];
$first_name = $_POST['firstName'];
$name = $_POST['name'];
$email = $_POST['email'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$address = $_POST['address'];
$city = $_POST['city'];
$postal_code = $_POST['postalCode'];
$country = $_POST['country'];

// Affichage des informations saisies dans le formulaire : 
echo escape_HTML($user_name);
echo escape_HTML($first_name);
echo escape_HTML($name);
echo escape_HTML($email);
echo escape_HTML($address);
echo escape_HTML($city);
echo escape_HTML($postal_code);
echo escape_HTML($country);

// Écrire un code qui permet d'afficher un message d'erreur si le mot de passe et sa confirmation ne correspondent pas :

if (!password_check($password1, $password2)) {
    echo "Attention ! Le mot de passe et la confirmation de mot de passe ne sont pas identiques";
    }


    // var_dump($_POST);