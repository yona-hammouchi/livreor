<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylsheet" href="../styles/connexion.css">
    <title>connexion</title>
</head>

<body>
    <?php require_once '../includes/navbar.php'; ?>

    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <section class="containers_head_forms">
        <section class="container_forms">
            <h1>Bienvenue au monde, petit trésor !</h1>
            <form action="inscription.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br><br>

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