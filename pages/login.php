<?php
session_start(); // Démarrer la session
require_once '../includes/navbar.php';
require_once '../class/Database.php';
<<<<<<< HEAD
require_once '../class/connexion.php';

session_start();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion = new Connexion();
    $result = $connexion->handleLogin($_POST);
    if (isset($result['error'])) {
        $error = $result['error'];
=======
require_once '../class/Connexion.php'; // Inclure la classe Connexion

// Instancier la classe Database et Connexion
$db = new Database();
$connexion = new Connexion($db);

// Gérer la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $connexion->handleLogin($_POST);

    // Afficher les erreurs ou rediriger
    if (isset($result['error'])) {
        $error_message = $result['error'];
    } else {
        // Redirection vers profil.php est gérée dans handleLogin()
        // Donc pas besoin de faire autre chose ici
>>>>>>> 1719a6a44fdda4391b8247ca561f00cd2ed130e6
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styleNavbar.css">
    <link rel="stylesheet" href="../styles/styleConnexion.css">
    <title>Connexion</title>
</head>

<body>
    <section class="containers_head_forms">
        <section class="container_forms">
            <p>Veuillez entrer vos informations pour vous connecter.</p>

<<<<<<< HEAD
            <!-- Affichage des erreurs -->
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
=======
            <!-- Afficher les messages d'erreur -->
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
>>>>>>> 1719a6a44fdda4391b8247ca561f00cd2ed130e6
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form action="" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required><br><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required><br><br>

                <button type="submit">Se connecter</button>
            </form>
        </section>
    </section>
<<<<<<< HEAD
=======

    <!-- Afficher un message de bienvenue si l'utilisateur est connecté -->
    <?php if (isset($_SESSION['username'])): ?>
        <p class="success">Bonjour <?php echo htmlspecialchars($_SESSION['username']); ?> !</p>
    <?php endif; ?>
>>>>>>> 1719a6a44fdda4391b8247ca561f00cd2ed130e6
</body>

</html>