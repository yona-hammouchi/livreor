<?php
session_start();
require_once '../includes/navbar.php';
require_once '../class/Database.php';
require_once '../class/Users.php'; // Inclure la classe Connexion

$db = new Database();
$connexion = new Connexion($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $connexion->handleLogin($_POST);


    if (isset($result['error'])) {
        $error_message = $result['error'];
    } else {
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
    <link rel="stylesheet" href="../styles/style_footer.css">
    <title>Connexion</title>
</head>

<body>
    <section class="containers_head_forms">
        <section class="container_forms">
            <p>Veuillez entrer vos informations pour vous connecter.</p>

            <!-- Afficher les messages d'erreur -->
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
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
    <?php if (isset($_SESSION['username'])): ?>
        <p class="success">Bonjour <?php echo htmlspecialchars($_SESSION['username']); ?> !</p>
    <?php endif; ?>
    <?php require_once '../includes/footer.php'; ?>
</body>

</html>