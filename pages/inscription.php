<?php
require_once '../class/connexion.php';
require_once '../class/Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>
    <h1>Inscription</h1>
    <form action="inscription.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">S'inscrire</button>
    </form>

    <?php if (isset($result['error'])): ?>
        <p style="color:red;"><?php echo $result['error']; ?></p>
    <?php elseif (isset($result['success'])): ?>
        <p style="color:green;"><?php echo $result['success']; ?></p>
    <?php endif; ?>
</body>

</html>