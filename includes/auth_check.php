<?php
// Si la clé user_id n'existe pas dans $_SESSION, c'est que l'utilisateur n'est pas connecté :
if (!isset($_SESSION['user_id'])) {

    // On redirige vers la page de connexion :
    header('Location: login.php');

    // On arrête l'exécution du reste du code :
    exit();
}