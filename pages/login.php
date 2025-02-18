<?php
session_start();
require_once '../includes/navbar.php';
require_once '../class/Database.php';
require_once '../class/Connexion.php';

$connexion = new Connexion();
$result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $connexion->handleLogin($_POST);
    if (isset($result['success'])) {
        $_SESSION['username'] = $_POST['username']; // Stocke le nom d'utilisateur
        header("Location: profil.php"); // Redirige vers la page profil
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

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
            <form action="login.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required><br><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required><br><br>

                <button type="submit">Se connecter</button>
            </form>
        </section>
    </section>

    <?php if (isset($_SESSION['username'])): ?>
        <p class="success">Bonjour <?php echo htmlspecialchars($_SESSION['username']); ?> !</p>
    <?php endif; ?>
</body>

</html>