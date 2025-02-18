<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: profil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>

<body>
    <h1>Bienvenue sur votre profil, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>

    <p>C'est votre espace personnel.</p>

    <form method="POST">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>

        <label>Nouveau mot de passe :</label>
        <input type="password" name="password" placeholder="Entrer le nouveau mot de passe">
        <button type="submit" name="update">Mettre à jour</button>

    </form>
    <a href="deconnexion.php">Se déconnecter</a>

    <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
        <button type="submit" name="delete" style="background-color: red; color: white;">Supprimer mon compte</button>
    </form>ettre à jour</button>
</body>

</html>

?>