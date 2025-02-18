<?php
require_once '../includes/navbar.php';
require_once '../class/Database.php';
require_once '../class/connexion.php';

session_start();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion = new Connexion();
    $result = $connexion->handleLogin($_POST);
    if (isset($result['error'])) {
        $error = $result['error'];
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

            <!-- Affichage des erreurs -->
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
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
</body>

</html>