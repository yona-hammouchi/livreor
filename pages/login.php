<body>
    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="email">Email de l'utilisateur</label>
        <input type="email" name="email" id="email" placeholder="Veuillez entrez votre mail" required>
        <br>
        <label for="password">Mot de passe de l'utilisateur</label>
        <input type="password" name="password" id="password" placeholder="Veuillez entre votre mot de passe" required>
        <br>
        <button type="submit">Login</button>
    </form>