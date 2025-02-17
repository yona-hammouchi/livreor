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
    <?php require_once '../includes/navbar.php'; ?>

    <!-- Message d'erreur général -->
    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <section class="containers_head_forms">
        <section class="container_forms">

            <h1>Bienvenue au monde, petit trésor !</h1>
            <p>Veuillez entrer vos informations pour vous connecter.</p>

            <form action="inscription.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required><br><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required><br><br>

                <button type="submit">Se connecter</button>
            </form>

            <div class="message">
                <?php if (isset($result['error'])): ?>
                    <p class="error"><?php echo $result['error']; ?></p>
                <?php elseif (isset($result['success'])): ?>
                    <p class="success"><?php echo $result['success']; ?></p>
                <?php endif; ?>
            </div>
        </section>
    </section>
</body>

</html>