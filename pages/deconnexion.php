<?php
session_start();  // Commence la session
session_unset();  // Supprime toutes les variables de session
session_destroy();  // Détruit la session

// Redirige vers la page de connexion après la déconnexion
header('Location: login.php');
exit;
?>
