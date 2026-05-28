<?php
session_start(); // Ouvrir la session pour pouvoir agir

// Vider le tableau $_SESSION pour effacer toutes les données en assignant un tableau vide :
$_SESSION = [];

// Détruire la session côté serveur :
session_destroy(); 

// Rediriger vers la page de connexion :
header('Location: login.php'); 
exit();